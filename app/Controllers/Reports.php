<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductsModel;
use App\Models\CategoriesModel;
use App\Models\CityModel;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\StoreModel;
use App\Models\UserModel;

class Reports extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        $role = session()->get('role');
        $productsModel = new ProductsModel();
        $categoriesModel = new CategoriesModel();
        $cityModel = new CityModel();
        $ordersModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $storesModel = new StoreModel();
        $userModel = new UserModel();

        $products = $productsModel->findAll();
        $categories = $categoriesModel->findAll();
        $cities = $cityModel->findAll();
        $stores = $storesModel->findAll();

        $orders = [];
        if ($role === 'admin') {
            // Admin: lihat semua transaksi
            $orders = $ordersModel->orderBy('created_at', 'DESC')->findAll();
        } elseif ($role === 'vendor') {
            // Vendor: lihat transaksi di tokonya
            $vendorId = session()->get('user_id');
            $store = $storesModel->where('user_id', $vendorId)->first();
            if ($store) {
                $orders = $ordersModel->where('store_id', $store['id'])->orderBy('created_at', 'DESC')->findAll();
            }
        } elseif ($role === 'customer') {
            // Customer: lihat transaksi sendiri
            $userId = session()->get('user_id');
            $orders = $ordersModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();
        }

        foreach ($orders as &$order) {
            $store = $storesModel->find($order['store_id']);
            $order['store_name'] = $store['name'] ?? '-';
            $order['store_address'] = $store['address'] ?? '-';
            // Ambil nama kota toko
            if (!empty($store['city_id'])) {
                $storeCity = $cityModel->find($store['city_id']);
                $order['store_city'] = $storeCity['name'] ?? '-';
            } else {
                $order['store_city'] = '-';
            }
            $order['store_phone'] = $store['phone'] ?? '-';
            $order['items'] = $orderItemsModel->where('order_id', $order['id'])->findAll();
            foreach ($order['items'] as &$item) {
                $product = $productsModel->find($item['product_id']);
                $item['product_name'] = $product['name'] ?? '-';
            }
            unset($item);
            $customer = $userModel->find($order['user_id']);
            $order['customer_name'] = $customer['fullname'] ?? '-';
            $order['customer_address'] = $customer['address'] ?? '-';
            // Ambil nama kota customer
            if (!empty($customer['city_id'])) {
                $customerCity = $cityModel->find($customer['city_id']);
                $order['customer_city'] = $customerCity['name'] ?? '-';
            } else {
                $order['customer_city'] = '-';
            }
            $order['customer_phone'] = $customer['phone'] ?? '-';
        }
        unset($order);

        return view('order_reports/index', [
            'orders' => $orders,
            'products' => $products,
            'categories' => $categories,
            'cities' => $cities,
            'stores' => $stores,
            'role' => $role,
        ]);
    }

    public function cancelOrder($orderId)
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/reports')->with('error', 'Aksi tidak diizinkan.');
        }
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->to('/reports')->with('error', 'Order tidak ditemukan.');
        }
        if ($order['status'] !== 'paid') {
            return redirect()->to('/reports')->with('error', 'Order hanya bisa dibatalkan jika status masih "paid".');
        }
        $orderModel->update($orderId, [
            'status' => 'cancelled',
        ]);
        return redirect()->to('/reports')->with('success', 'Order berhasil dibatalkan.');
    }

    public function finishOrder($orderId)
    {
        if (session()->get('role') !== 'customer') {
            return redirect()->to('/reports')->with('error', 'Aksi tidak diizinkan.');
        }
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->to('/reports')->with('error', 'Order tidak ditemukan.');
        }
        if ($order['status'] !== 'arrived') {
            return redirect()->to('/reports')->with('error', 'Order hanya bisa diselesaikan jika status sudah "arrived".');
        }
        $orderModel->update($orderId, [
            'status' => 'finish',
        ]);
        return redirect()->to('/reports')->with('success', 'Order berhasil diselesaikan.');
    }

    public function printInvoice($orderId)
    {
        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $productsModel = new ProductsModel();
        $storesModel = new StoreModel();
        $userModel = new UserModel();
        $cityModel = new CityModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->to('/reports')->with('error', 'Order tidak ditemukan.');
        }
        $allowedStatus = ['ready', 'shipped', 'arrived', 'finish'];
        if (!in_array($order['status'], $allowedStatus)) {
            return redirect()->to('/reports')->with('error', 'Invoice hanya bisa dicetak jika status order sudah siap, dikirim, tiba, atau selesai.');
        }
        $store = $storesModel->find($order['store_id']);
        $customer = $userModel->find($order['user_id']);
        $items = $orderItemsModel->where('order_id', $orderId)->findAll();
        foreach ($items as &$item) {
            $product = $productsModel->find($item['product_id']);
            $item['product_name'] = $product['name'] ?? '-';
        }
        unset($item);
        $storeCity = $store && !empty($store['city_id']) ? $cityModel->find($store['city_id']) : null;
        $customerCity = $customer && !empty($customer['city_id']) ? $cityModel->find($customer['city_id']) : null;
        // Generate PDF mirip vendor
        $pdf = new \App\Libraries\InvoicePdf();
        $storeName = $store['name'] ?? 'Toko';
        $pdf->setHeaderTitle($storeName);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Ln(10);
        $pdf->Cell(0, 8, 'Invoice #: ' . ($order['order_number'] ?? $order['id']), 0, 1, 'L');
        $left = "Nama: " . ($customer['fullname'] ?? $customer['username'] ?? '-') . "\n";
        $left .= "Alamat: " . ($customer['address'] ?? '-') . ', ' . ($customerCity['name'] ?? '-') . "\n";
        $left .= "No. HP: " . ($customer['phone'] ?? '-');
        $right = "Tanggal Invoice: " . date('d-m-Y', strtotime($order['created_at'])) . "\n";
        $right .= "Payment Gateway: " . ($order['payment_gateway'] ?? '-') . "\n";
        $right .= "Jenis Pembayaran: " . ($order['payment_method'] ?? '-');
        $pdf->MultiCell(90, 6, $left, 0, 'L', false, 0);
        $pdf->MultiCell(90, 6, $right, 0, 'L', false, 1);
        $pdf->Ln(5);
        $tbl = '<table border="1" cellpadding="4">'
            . '<thead><tr>'
            . '<th width="40">No</th>'
            . '<th width="250">Nama Produk</th>'
            . '<th width="50">Jumlah</th>'
            . '<th width="100">Harga Jual</th>'
            . '<th width="100">Subtotal</th>'
            . '</tr></thead><tbody>';
        $no = 1;
        foreach ($items as $item) {
            $qty = isset($item['quantity']) ? $item['quantity'] : (isset($item['qty']) ? $item['qty'] : 0);
            $tbl .= '<tr>';
            $tbl .= '<td width="40" align="center">' . $no++ . '</td>';
            $tbl .= '<td width="250">' . htmlspecialchars($item['product_name']) . '</td>';
            $tbl .= '<td width="50" align="center">' . $qty . '</td>';
            $tbl .= '<td width="100" align="right">Rp. ' . number_format($item['price'], 0, ',', '.') . '</td>';
            $tbl .= '<td width="100" align="right">Rp. ' . number_format($qty * $item['price'], 0, ',', '.') . '</td>';
            $tbl .= '</tr>';
        }
        $tbl .= '</tbody></table>';
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Grand Total: Rp. ' . number_format($order['total_amount'], 0, ',', '.'), 0, 1, 'R');
        $pdf->Output('invoice_order_' . ($order['order_number'] ?? $order['id']) . '.pdf', 'I');
        exit;
    }
}
