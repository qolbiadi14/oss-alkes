<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function doLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Set city_id ke session
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'isLoggedIn' => true,
                'city_id' => $user['city_id'] ?? null
            ]);

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else if ($user['role'] === 'customer') {
                return redirect()->to('/customer/dashboard');
            } else {
                // fallback jika role tidak dikenali
                return redirect()->to('/');
            }
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        $cityModel = new \App\Models\CityModel();
        $cities = $cityModel->orderBy('name', 'asc')->findAll();
        return view('register', ['cities' => $cities]);
    }

    public function doRegister()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'fullname' => 'required',
            'username' => 'required|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'birth_date' => 'required',
            'gender' => 'required|in_list[L,P]',
            'address' => 'required',
            'city_id' => 'required|integer',
            'phone' => 'permit_empty',
            'role' => 'required|in_list[customer,vendor]', // validasi role
        ]);

        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $birthDateInput = $this->request->getPost('birth_date');
        // Parse sesuai format MM/DD/YYYY dari datepicker
        $birthDate = null;
        if ($birthDateInput) {
            $date = date_create_from_format('m/d/Y', $birthDateInput);
            if ($date) {
                $birthDate = $date->format('Y-m-d');
            } else {
                // fallback: coba format lain jika perlu
                $birthDate = date('Y-m-d', strtotime($birthDateInput));
            }
        }
        $userModel = new \App\Models\UserModel();
        $userModel->insert([
            'fullname' => $this->request->getPost('fullname'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'), // ambil dari input
            'birth_date' => $birthDate,
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'city_id' => $this->request->getPost('city_id'),
            'phone' => $this->request->getPost('phone'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }
}
