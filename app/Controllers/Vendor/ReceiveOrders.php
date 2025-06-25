<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\ProductsModel;
use App\Models\CityModel;
use App\Models\CategoriesModel;
use App\Models\UserModel;
use App\Models\StoreModel;
use CodeIgniter\I18n\Time;
use App\Libraries\InvoicePdf;

class ReceiveOrders extends BaseController
{
    public function index()
    {
        // Pastikan user sudah login dan memiliki role 'vendor'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'vendor') {
            return redirect()->to('/login');
        }

        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $productsModel = new ProductsModel();
        $cityModel = new CityModel();
        $categoriesModel = new CategoriesModel();
        $userModel = new UserModel();
        $storeModel = new StoreModel();

        // Ambil store_id milik vendor yang sedang login
        $vendorId = session()->get('user_id');
        $store = $storeModel->where('user_id', $vendorId)->first();
        $storeId = $store['id'] ?? null;

        // Ambil data order dengan status 'paid' untuk toko ini
        $orders = $orderModel->where('status', 'paid')->where('store_id', $storeId)->findAll();

        foreach ($orders as &$order) {
            $order['items'] = $orderItemsModel->where('order_id', $order['id'])->findAll();
            foreach ($order['items'] as &$item) {
                $product = $productsModel->find($item['product_id']);
                $item['product_name'] = $product['name'] ?? '-';
            }
            unset($item);
            // Ambil data user (customer)
            $customer = $userModel->find($order['user_id']);
            $order['customer_name'] = $customer['fullname'] ?? $customer['username'] ?? '-';
            $order['customer_address'] = $customer['address'] ?? '-';
            $order['customer_phone'] = $customer['phone'] ?? '-';
            // Ambil nama kota
            $city = $cityModel->find($customer['city_id'] ?? null);
            $order['customer_city'] = $city['name'] ?? '-';
        }
        unset($order);

        return view('vendor/receive_orders', [
            'orders' => $orders,
            'cities' => $cityModel->findAll(),
            'categories' => $categoriesModel->findAll(),
            'users' => $userModel->findAll(),
        ]);
    }

    public function accept($orderId)
    {
        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $productsModel = new ProductsModel();
        $userModel = new UserModel();
        $storeModel = new StoreModel();
        $cityModel = new CityModel();
        $order = $orderModel->find($orderId);
        if (!$order || $order['status'] !== 'paid') {
            return redirect()->back()->with('error', 'Order tidak valid atau sudah diproses.');
        }
        // Kurangi stok barang
        $items = $orderItemsModel->where('order_id', $orderId)->findAll();
        foreach ($items as $item) {
            $product = $productsModel->find($item['product_id']);
            if ($product && $product['stock'] >= $item['quantity']) {
                $productsModel->update($item['product_id'], [
                    'stock' => $product['stock'] - $item['quantity']
                ]);
            } else {
                return redirect()->back()->with('error', 'Stok barang tidak cukup untuk ' . ($product['name'] ?? 'Barang Tidak Diketahui'));
            }
        }
        // Lengkapi items dengan product_name
        foreach ($items as &$item) {
            $product = $productsModel->find($item['product_id']);
            $item['product_name'] = $product['name'] ?? '-';
        }
        unset($item);
        // Ubah status order menjadi 'ready'
        $orderModel->update($orderId, [
            'status' => 'ready',
            'updated_at' => Time::now('Asia/Jakarta', 'en_ID')
        ]);
        // Kirim invoice ke email customer
        $customer = $userModel->find($order['user_id']);
        if ($customer && !empty($customer['email'])) {
            // Generate PDF invoice
            $pdf = new InvoicePdf();
            $storeName = $storeModel->find($order['store_id'])['name'] ?? 'Toko';
            $pdf->setHeaderTitle($storeName);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Ln(10);
            $pdf->Cell(0, 8, 'Invoice #: ' . ($order['order_number'] ?? $order['id']), 0, 1, 'L');
            $city = $cityModel->find($customer['city_id'] ?? null);
            $left = "Nama: " . ($customer['fullname'] ?? $customer['username'] ?? '-') . "\n";
            $left .= "Alamat: " . ($customer['address'] ?? '-') . ', ' . ($city['name'] ?? '-') . "\n";
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
            // Output PDF ke string
            $pdfContent = $pdf->Output('invoice.pdf', 'S');
            $email = \Config\Services::email();
            $email->setTo($customer['email']);
            $email->setSubject('Invoice Order #' . $order['order_number']);
            $email->setMessage('Silakan lihat lampiran untuk invoice pesanan Anda.');
            $email->attach($pdfContent, 'attachment', 'invoice.pdf', 'application/pdf');
            $email->send();
        }
        return redirect()->back()->with('success', 'Order berhasil diterima dan invoice dikirim ke customer.');
    }
}
