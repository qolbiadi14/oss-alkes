<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\ProductsModel;
use App\Models\CategoriesModel;
use App\Models\StoreModel;
use App\Models\UserModel;

class Products extends \App\Controllers\BaseController
{
    protected $productsModel;
    protected $categoriesModel;
    protected $storeModel;

    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        $this->categoriesModel = new CategoriesModel();
        $this->storeModel = new StoreModel();
    }

    private function getCurrentStoreId()
    {
        $userId = session()->get('user_id');
        if (!$userId) return null;
        $store = $this->storeModel->where('user_id', $userId)->first();
        return $store ? $store['id'] : null;
    }

    private function getCurrentStore()
    {
        $userId = session()->get('user_id');
        if (!$userId) return null;
        return $this->storeModel->where('user_id', $userId)->first();
    }

    private function checkStoreActiveOrRedirect()
    {
        $store = $this->getCurrentStore();
        if (!$store) {
            session()->setFlashdata('errors', ['Anda belum membuat toko. Silakan lengkapi identitas toko Anda.']);
            return redirect()->to('/vendor/storeidentity');
        }
        if (!in_array($store['status'], ['active', 'approved'])) {
            $msg = $store['status'] === 'pending' ? 'Toko Anda masih dalam proses verifikasi.' : 'Toko Anda ditolak. Silakan hubungi admin.';
            session()->setFlashdata('errors', [$msg]);
            return redirect()->to('/vendor/storeidentity');
        }
        return null;
    }

    public function index()
    {
        $redirect = $this->checkStoreActiveOrRedirect();
        if ($redirect) return $redirect;
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;
        $storeId = $this->getCurrentStoreId();
        $products = [];
        $pager = null;
        if ($storeId) {
            $products = $this->productsModel->where('store_id', $storeId)->paginate($perPage, 'products');
            $pager = $this->productsModel->pager;
        }
        // Ambil semua kategori dan buat array id=>name
        $categories = $this->categoriesModel->findAll();
        $categoriesMap = [];
        foreach ($categories as $cat) {
            $categoriesMap[$cat['id']] = $cat['name'];
        }
        $data = [
            'products' => $products,
            'pager' => $pager,
            'currentPage' => $page,
            'categoriesMap' => $categoriesMap,
        ];
        return view('vendor/products/index', $data);
    }

    public function add()
    {
        $redirect = $this->checkStoreActiveOrRedirect();
        if ($redirect) return $redirect;
        // Ambil semua kategori dan buat array id=>name
        $categories = $this->categoriesModel->findAll();
        $categoriesMap = [];
        foreach ($categories as $cat) {
            $categoriesMap[$cat['id']] = $cat['name'];
        }
        return view('vendor/products/add', ['categoriesMap' => $categoriesMap]);
    }

    public function store()
    {
        $redirect = $this->checkStoreActiveOrRedirect();
        if ($redirect) return $redirect;
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required',
            'description' => 'permit_empty',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer',
            'image' => 'permit_empty|uploaded[image]|is_image[image]|max_size[image,2048]',
        ]);
        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $imageName = null;
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move('writable/uploads', $imageName);
        }
        $storeId = $this->getCurrentStoreId();
        if (!$storeId) {
            return redirect()->back()->with('errors', ['Store tidak ditemukan untuk user ini.']);
        }
        $this->productsModel->insert([
            'store_id' => $storeId,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'category_id' => $this->request->getPost('category_id'),
            'image' => $imageName,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/vendor/products')->with('success', 'Produk berhasil disimpan.');
    }

    public function edit($id)
    {
        $redirect = $this->checkStoreActiveOrRedirect();
        if ($redirect) return $redirect;
        $product = $this->productsModel->find($id);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan');
        }
        // Ambil semua kategori dan buat array id=>name
        $categories = $this->categoriesModel->findAll();
        $categoriesMap = [];
        foreach ($categories as $cat) {
            $categoriesMap[$cat['id']] = $cat['name'];
        }
        return view('vendor/products/edit', [
            'product' => $product,
            'categoriesMap' => $categoriesMap,
        ]);
    }

    public function update($id)
    {
        $redirect = $this->checkStoreActiveOrRedirect();
        if ($redirect) return $redirect;
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required',
            'description' => 'permit_empty',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer',
            'image' => 'permit_empty|uploaded[image]|is_image[image]|max_size[image,2048]',
        ]);
        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $product = $this->productsModel->find($id);
        $storeId = $this->getCurrentStoreId();
        if (!$product || $product['store_id'] != $storeId) {
            return redirect()->to('/vendor/products')->with('errors', ['Produk tidak ditemukan atau bukan milik toko Anda.']);
        }
        $imageName = $product['image'] ?? null;
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move('writable/uploads', $imageName);
        }
        $this->productsModel->update($id, [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'category_id' => $this->request->getPost('category_id'),
            'image' => $imageName,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/vendor/products')->with('success', 'Produk berhasil diupdate.');
    }

    public function delete($id)
    {
        $redirect = $this->checkStoreActiveOrRedirect();
        if ($redirect) return $redirect;
        $product = $this->productsModel->find($id);
        $storeId = $this->getCurrentStoreId();
        if (!$product || $product['store_id'] != $storeId) {
            return redirect()->to('/vendor/products')->with('errors', ['Produk tidak ditemukan atau bukan milik toko Anda.']);
        }
        $this->productsModel->delete($id);
        return redirect()->to('/vendor/products')->with('success', 'Produk berhasil dihapus.');
    }

    // Add more methods as needed
}
