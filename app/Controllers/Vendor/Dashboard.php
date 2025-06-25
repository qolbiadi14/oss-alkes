<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Dashboard extends \App\Controllers\BaseController
{
    public function index()
    {
        // Pastikan user sudah login dan memiliki role 'vendor'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'vendor') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $storeModel = new \App\Models\StoreModel();
        $store = $storeModel->where('user_id', $userId)->first();
        $storeId = $store ? $store['id'] : null;

        $productsCount = 0;
        $ordersCount = 0;
        $paidOrdersCount = 0;

        if ($storeId) {
            $productsModel = new \App\Models\ProductsModel();
            $orderModel = new \App\Models\OrderModel();
            $productsCount = $productsModel->where('store_id', $storeId)->countAllResults();
            $ordersCount = $orderModel->where('store_id', $storeId)->countAllResults();
            $paidOrdersCount = $orderModel->where('store_id', $storeId)->where('status', 'paid')->countAllResults();
        }

        return view('vendor/dashboard', [
            'productsCount' => $productsCount,
            'ordersCount' => $ordersCount,
            'paidOrdersCount' => $paidOrdersCount
        ]);
    }
}
