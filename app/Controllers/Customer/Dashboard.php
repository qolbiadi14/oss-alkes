<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProductsModel;
use App\Models\StoreModel;
use App\Models\CategoriesModel;

class Dashboard extends \App\Controllers\BaseController
{
    /**
     * Menampilkan halaman dashboard customer.
     *
     * Fungsi ini akan:
     * - Memastikan user sudah login dan berperan sebagai customer.
     * - Mengambil data produk (bisa difilter berdasarkan kategori).
     * - Mengambil data semua toko dan membuat map id => nama toko.
     * - Mengambil data semua kategori dan membuat map id => nama kategori.
     * - Mengirim data ke view dashboard customer.
     *
     * @return string
     */
    public function index()
    {
        // Pastikan user sudah login dan memiliki role 'admin'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'customer') {
            return redirect()->to('/login');
        }

        $productsModel = new ProductsModel();
        $storeModel = new StoreModel();
        $categoriesModel = new CategoriesModel();

        $categoryId = $this->request->getGet('category');
        if ($categoryId) {
            $products = $productsModel->where('category_id', $categoryId)->findAll();
        } else {
            $products = $productsModel->findAll();
        }
        // Ambil semua toko, buat map id => nama toko
        $stores = $storeModel->findAll();
        $storesMap = [];
        foreach ($stores as $store) {
            $storesMap[$store['id']] = $store['name'];
        }
        // Ambil semua kategori
        $categories = $categoriesModel->findAll();
        // Buat map kategori id => nama
        $categoriesMap = [];
        foreach ($categories as $cat) {
            $categoriesMap[$cat['id']] = $cat['name'];
        }

        return view('customer/dashboard', [
            'products' => $products,
            'storesMap' => $storesMap,
            'categories' => $categories,
            'categoriesMap' => $categoriesMap,
        ]);
    }
}
