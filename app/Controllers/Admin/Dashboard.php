<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Dashboard extends \App\Controllers\BaseController
{
    public function index()
    {
        // Pastikan user sudah login dan memiliki role 'admin'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $userModel = new \App\Models\UserModel();
        $storeModel = new \App\Models\StoreModel();
        $orderModel = new \App\Models\OrderModel();

        $totalUsers = $userModel->countAllResults();
        $totalStores = $storeModel->countAllResults();
        $totalOrders = $orderModel->countAllResults();
        $pendingOrders = $orderModel->whereIn('status', ['ready', 'shipped'])->countAllResults();

        return view('admin/dashboard', [
            'totalUsers' => $totalUsers,
            'totalStores' => $totalStores,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders
        ]);
    }
}
