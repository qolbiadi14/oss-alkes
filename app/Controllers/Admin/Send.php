<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\StoreModel;
use App\Models\UserModel;
use App\Models\CityModel;
use App\Models\ProductsModel;

class Send extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $storeModel = new StoreModel();
        $userModel = new UserModel();
        $cityModel = new CityModel();
        $productsModel = new ProductsModel();

        // Ambil order dengan status ready atau shipped
        $orders = $orderModel->whereIn('status', ['ready', 'shipped'])->orderBy('created_at', 'DESC')->findAll();
        foreach ($orders as &$order) {
            $store = $storeModel->find($order['store_id']);
            $order['store_address'] = $store['address'] ?? '-';
            $order['store_city'] = isset($store['city_id']) ? ($cityModel->find($store['city_id'])['name'] ?? '-') : '-';
            $order['store_phone'] = $store['phone'] ?? '-';
            $customer = $userModel->find($order['user_id']);
            $order['customer_name'] = $customer['fullname'] ?? '-';
            $order['customer_address'] = $customer['address'] ?? '-';
            $order['customer_city'] = isset($customer['city_id']) ? ($cityModel->find($customer['city_id'])['name'] ?? '-') : '-';
            $order['customer_phone'] = $customer['phone'] ?? '-';
            $order['items'] = $orderItemsModel->where('order_id', $order['id'])->findAll();
            foreach ($order['items'] as &$item) {
                $product = $productsModel->find($item['product_id']);
                $item['product_name'] = $product['name'] ?? '-';
            }
            unset($item);
        }
        unset($order);
        return view('admin/index_send', [
            'orders' => $orders
        ]);
    }

    public function updateStatus($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Order tidak ditemukan.');
        }
        if ($order['status'] === 'ready') {
            $orderModel->update($orderId, ['status' => 'shipped']);
            return redirect()->back()->with('success', 'Status order diubah menjadi shipped.');
        } elseif ($order['status'] === 'shipped') {
            $orderModel->update($orderId, ['status' => 'arrived']);
            return redirect()->back()->with('success', 'Status order diubah menjadi arrived.');
        }
        return redirect()->back()->with('error', 'Aksi tidak valid.');
    }
}
