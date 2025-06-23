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

        // Ambil data user dari model
        $userModel = new UserModel();
        $users = $userModel->findAll();

        // Tampilkan view dashboard dengan data user
        return view('admin/dashboard', ['users' => $users]);
    }
}

?>