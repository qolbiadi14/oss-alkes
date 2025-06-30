<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\ProductsModel;
use App\Models\StoreModel;
use App\Models\UserModel;
use App\Models\CityModel;

class Payment extends BaseController
{
    /**
     * Memproses pembuatan order dari cart user.
     *
     * - Mengecek user login dan role customer.
     * - Mengambil data cart dari session.
     * - Membuat nomor order unik.
     * - Menghitung total amount order.
     * - Menyimpan order dan order items ke database.
     * - Mengosongkan cart dan redirect ke halaman payment.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function process()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'customer') {
            return redirect()->to('/login');
        }
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart kosong.');
        }
        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemsModel();
        $productsModel = new ProductsModel();
        // Generate order_number: INVYYYMMDDXXX
        $prefix = 'INV' . date('Ymd');
        $lastOrder = $orderModel->like('order_number', $prefix, 'after')->orderBy('order_number', 'DESC')->first();
        $lastNumber = 0;
        if ($lastOrder && preg_match('/INV\d{8}(\d{3})/', $lastOrder['order_number'], $m)) {
            $lastNumber = (int)$m[1];
        }
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $orderNumber = $prefix . $newNumber;
        // Ambil store_id dari produk pertama
        $firstProduct = reset($cart);
        $product = $productsModel->find($firstProduct['id']);
        $storeId = $product ? $product['store_id'] : null;
        // Hitung total_amount
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['qty'] * $item['price'];
        }
        // Simpan order
        $orderData = [
            'order_number' => $orderNumber,
            'store_id'     => $storeId,
            'user_id'      => session()->get('user_id'),
            'total_amount' => $totalAmount,
            'status'       => 'pending',
            'payment_status' => 'pending',
        ];
        $orderId = $orderModel->insert($orderData);
        // Simpan order items
        foreach ($cart as $item) {
            $orderItemModel->insert([
                'order_id'   => $orderId,
                'product_id' => $item['id'],
                'quantity'   => $item['qty'],
                'price'      => $item['price'],
            ]);
        }
        // Kosongkan cart
        session()->remove('cart');
        // Redirect ke halaman payment dengan order_id
        return redirect()->to('/customer/payment/' . $orderId);
    }

    /**
     * Menampilkan halaman pembayaran untuk order tertentu.
     *
     * - Mengecek user login dan role customer.
     * - Mengambil data order, order items, toko, user, dan kota terkait.
     * - Mengirim data ke view payment.
     *
     * @param int|null $orderId
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function index($orderId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'customer') {
            return redirect()->to('/login');
        }
        if (!$orderId) {
            return redirect()->to('/customer/cart');
        }
        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemsModel();
        $storeModel = new StoreModel();
        $userModel = new UserModel();
        $productsModel = new ProductsModel();
        $cityModel = new CityModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->to('/customer/cart')->with('error', 'Order tidak ditemukan.');
        }
        $orderItems = $orderItemModel->where('order_id', $orderId)->findAll();
        // Ambil data toko
        $store = $storeModel->find($order['store_id']);
        // Ambil data user
        $user = $userModel->find($order['user_id']);
        // Ambil nama produk untuk setiap order item
        foreach ($orderItems as &$item) {
            $product = $productsModel->find($item['product_id']);
            $item['product_name'] = $product ? $product['name'] : '-';
        }
        unset($item);
        $storeCityName = isset($store['city_id']) ? ($cityModel->find($store['city_id'])['name'] ?? '-') : '-';
        $userCityName = isset($user['city_id']) ? ($cityModel->find($user['city_id'])['name'] ?? '-') : '-';
        return view('customer/payment', [
            'order' => $order,
            'orderItems' => $orderItems,
            'store' => $store,
            'user' => $user,
            'store_city_name' => $storeCityName,
            'user_city_name' => $userCityName,
        ]);
    }

    /**
     * Menghasilkan Snap Token Midtrans untuk order tertentu (AJAX).
     *
     * - Mengambil data order.
     * - Mengatur konfigurasi Midtrans.
     * - Mengembalikan snapToken dalam format JSON.
     *
     * @param int $orderId
     * @return \CodeIgniter\HTTP\Response
     */
    public function snapToken($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return $this->response->setJSON(['error' => 'Order tidak ditemukan']);
        }
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        $params = [
            'transaction_details' => [
                'order_id' => $order['order_number'],
                'gross_amount' => $order['total_amount'],
            ],
            'customer_details' => [
                'first_name' => $this->request->user['fullname'] ?? 'Customer',
                'email' => $this->request->user['email'] ?? '',
            ],
        ];
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return $this->response->setJSON(['snapToken' => $snapToken]);
    }

    /**
     * Memproses pembayaran dengan metode prepaid (bayar di muka).
     *
     * - Mengambil data order.
     * - Menghasilkan snapToken dan update order.
     * - Redirect ke halaman payment dengan snapToken.
     *
     * @param int $orderId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function payPrepaid($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Order tidak ditemukan.');
        }
        $snapToken = $this->generateSnapToken($order);
        $orderModel->update($orderId, [
            'payment_method' => 'prepaid',
            'payment_token' => $snapToken,
            'payment_gateway' => 'midtrans',
        ]);
        return redirect()->to('/customer/payment/' . $orderId . '?snapToken=' . $snapToken);
    }

    /**
     * Memproses pembayaran dengan metode postpaid (bayar di tempat, kota sama).
     *
     * - Mengecek kecocokan kota user dan toko.
     * - Menghasilkan snapToken dan update order.
     * - Redirect ke halaman payment dengan snapToken.
     *
     * @param int $orderId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function payPostpaid($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Order tidak ditemukan.');
        }
        // Ambil data store dan user
        $storeModel = new StoreModel();
        $userModel = new UserModel();
        $store = $storeModel->find($order['store_id']);
        $user = $userModel->find($order['user_id']);
        // Cek city_id
        if (!isset($store['city_id']) || !isset($user['city_id']) || $store['city_id'] != $user['city_id']) {
            return redirect()->back()->with('error', 'Pembayaran postpaid hanya bisa dilakukan jika kota toko dan kota Anda sama.');
        }
        $snapToken = $this->generateSnapToken($order);
        $orderModel->update($orderId, [
            'payment_method' => 'postpaid',
            'payment_token' => $snapToken,
            'payment_gateway' => 'midtrans',
        ]);
        return redirect()->to('/customer/payment/' . $orderId . '?snapToken=' . $snapToken);
    }

    /**
     * Membuat Snap Token Midtrans untuk order (internal).
     *
     * - Mengatur konfigurasi Midtrans.
     * - Mengembalikan snapToken.
     *
     * @param array $order
     * @return string
     */
    private function generateSnapToken($order)
    {
        \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        $params = [
            'transaction_details' => [
                'order_id' => $order['order_number'],
                'gross_amount' => $order['total_amount'],
            ],
        ];
        return \Midtrans\Snap::getSnapToken($params);
    }

    /**
     * Menangani kasus pembayaran gagal.
     *
     * - Mengupdate status order menjadi cancelled dan payment_status failed.
     * - Redirect ke halaman laporan dengan pesan error.
     *
     * @param int $orderId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function handlePaymentFailed($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->to('/customer/reports')->with('error', 'Order tidak ditemukan.');
        }
        $orderModel->update($orderId, [
            'status' => 'cancelled',
            'payment_status' => 'failed',
        ]);
        return redirect()->to('/customer/reports')->with('error', 'Pembayaran gagal. Order dibatalkan.');
    }

    /**
     * Menangani kasus pembayaran sukses.
     *
     * - Mengupdate status order menjadi paid dan payment_status success.
     * - Redirect ke halaman laporan dengan pesan sukses.
     *
     * @param int $orderId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function handlePaymentSuccess($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->to('/customer/reports')->with('error', 'Order tidak ditemukan.');
        }
        $orderModel->update($orderId, [
            'status' => 'paid',
            'payment_status' => 'success',
        ]);
        return redirect()->to('/customer/reports')->with('success', 'Pembayaran berhasil. Order telah dibayar.');
    }
}
