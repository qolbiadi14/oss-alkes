<?php

namespace App\Controllers\Vendor;

use App\Controllers\BaseController;
use App\Models\ProductsModel;
use App\Models\CategoriesModel;

class Products extends \App\Controllers\BaseController
{
    protected $productsModel;
    protected $categoriesModel;

    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        $this->categoriesModel = new CategoriesModel();
    }

    public function index()
    {
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;
        $products = $this->productsModel->paginate($perPage, 'products');
        $pager = $this->productsModel->pager;
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
        return view('Vendor/products/index', $data);
    }

    public function add()
    {
        // Ambil semua kategori dan buat array id=>name
        $categories = $this->categoriesModel->findAll();
        $categoriesMap = [];
        foreach ($categories as $cat) {
            $categoriesMap[$cat['id']] = $cat['name'];
        }
        return view('Vendor/products/add', ['categoriesMap' => $categoriesMap]);
    }

    public function store()
    {
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
        $this->productsModel->insert([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'category_id' => $this->request->getPost('category_id'),
            'image' => $imageName,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/Vendor/products')->with('success', 'Produk berhasil disimpan.');
    }

    public function edit($id)
    {
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
        return view('Vendor/products/edit', [
            'product' => $product,
            'categoriesMap' => $categoriesMap,
        ]);
    }

    public function update($id)
    {
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
        return redirect()->to('/Vendor/products')->with('success', 'Produk berhasil diupdate.');
    }

    public function delete($id)
    {
        $product = $this->productsModel->find($id);
        if (!$product) {
            return redirect()->to('/Vendor/products')->with('errors', ['Produk tidak ditemukan.']);
        }
        $this->productsModel->delete($id);
        return redirect()->to('/Vendor/products')->with('success', 'Produk berhasil dihapus.');
    }

    // Add more methods as needed
}
