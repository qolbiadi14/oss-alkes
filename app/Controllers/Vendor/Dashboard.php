<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Dashboard extends \App\Controllers\BaseController
{
    public function index()
    {
        // Pastikan user sudah login dan memiliki role 'admin'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'vendor') {
            return redirect()->to('/login');
        }

        // Ambil data user dari model
        $userModel = new UserModel();
        $users = $userModel->findAll();

        // Tampilkan view dashboard dengan data user
        return view('vendor/dashboard', ['users' => $users]);
    }
}
