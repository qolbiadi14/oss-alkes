<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StoreModel;
use App\Models\CityModel;
use App\Models\UserModel;

class AccStores extends BaseController
{
    protected $storeModel;
    protected $cityModel;
    protected $userModel;

    public function __construct()
    {
        $this->storeModel = new StoreModel();
        $this->cityModel = new CityModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $stores = $this->storeModel->findAll();
        $cityIds = array_column($stores, 'city_id');
        $cities = [];
        if (!empty($cityIds)) {
            $cityList = $this->cityModel->whereIn('id', $cityIds)->findAll();
            foreach ($cityList as $city) {
                $cities[$city['id']] = $city['name'];
            }
        }
        $data['stores'] = $stores;
        $data['cities'] = $cities;
        return view('admin/index_accstore', $data);
    }

    public function approve($id)
    {
        $this->storeModel->update($id, ['status' => 'approved']);
        return redirect()->to('/admin/accstores')->with('success', 'Store berhasil di-approve.');
    }

    public function reject($id)
    {
        $this->storeModel->update($id, ['status' => 'rejected']);
        return redirect()->to('/admin/accstores')->with('success', 'Store berhasil di-reject.');
    }

    public function suspend($id)
    {
        $this->storeModel->update($id, ['status' => 'suspended']);
        return redirect()->to('/admin/accstores')->with('success', 'Store berhasil di-suspend.');
    }

    public function unsuspend($id)
    {
        $this->storeModel->update($id, ['status' => 'approved']);
        return redirect()->to('/admin/accstores')->with('success', 'Store berhasil di-unsuspend.');
    }
}
