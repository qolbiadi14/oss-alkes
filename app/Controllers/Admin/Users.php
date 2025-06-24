<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CityModel;

class Users extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $cityModel = new CityModel();
        $currentPage = (int)($this->request->getGet('page') ?? 1);
        $perPage = 10;

        // Ambil user yang bukan admin
        $users = $userModel
            ->where('role !=', 'admin')
            ->orderBy('id', 'DESC')
            ->paginate($perPage, 'users');
        $pager = $userModel->pager;

        // Ambil data kota
        $cityIds = array_column($users, 'city_id');
        $cities = [];
        if (!empty($cityIds)) {
            $cityRows = $cityModel->whereIn('id', $cityIds)->findAll();
            foreach ($cityRows as $city) {
                $cities[$city['id']] = $city['name'];
            }
        }

        return view('admin/index_user', [
            'users' => $users,
            'cities' => $cities,
            'pager' => $pager,
            'currentPage' => $currentPage,
        ]);
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user || $user['role'] === 'admin') {
            return redirect()->back()->with('error', 'User tidak ditemukan atau tidak dapat di-nonaktifkan.');
        }
        $userModel->update($id, ['status' => 'inactive']);
        return redirect()->back()->with('success', 'User berhasil dinonaktifkan.');
    }

    public function activate($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user || $user['role'] === 'admin') {
            return redirect()->back()->with('error', 'User tidak ditemukan atau tidak dapat diaktifkan.');
        }
        $userModel->update($id, ['status' => 'active']);
        return redirect()->back()->with('success', 'User berhasil diaktifkan kembali.');
    }
}
