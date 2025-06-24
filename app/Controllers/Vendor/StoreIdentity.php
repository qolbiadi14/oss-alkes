<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\StoreModel;
use App\Models\CityModel;

class StoreIdentity extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $storeModel = new StoreModel();
        $cityModel = new CityModel();
        $store = $storeModel->where('user_id', $userId)->first();
        $cities = $cityModel->findAll();

        return view('vendor/storeidentity', [
            'store' => $store,
            'cities' => $cities
        ]);
    }

    public function save()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }
        $storeModel = new StoreModel();
        $store = $storeModel->where('user_id', $userId)->first();

        $data = [
            'user_id' => $userId,
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'city_id' => $this->request->getPost('city_id'),
            'phone' => $this->request->getPost('phone'),
        ];

        if (!$store) {
            // Store baru, status pending
            $data['status'] = 'pending';
            $storeModel->insert($data);
        } else {
            // Update, status tidak bisa diubah
            $storeModel->update($store['id'], $data);
        }
        return redirect()->to('/vendor/storeidentity');
    }
}
