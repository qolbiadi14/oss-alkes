<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoriesModel;

class Categories extends \App\Controllers\BaseController
{
    protected $categoriesModel;

    public function __construct()
    {
        $this->categoriesModel = new CategoriesModel();
    }

    public function index()
    {
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;
        $categories = $this->categoriesModel->paginate($perPage, 'categories');
        $pager = $this->categoriesModel->pager;
        $data = [
            'categories' => $categories,
            'pager' => $pager,
            'currentPage' => $page,
        ];
        return view('admin/categories/index', $data);
    }

    public function add()
    {
        return view('admin/categories/add');
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required',
            'description' => 'permit_empty',
        ]);
        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $this->categoriesModel->insert([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil disimpan.');
    }

    public function edit($id)
    {
        $category = $this->categoriesModel->find($id);
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan');
        }
        return view('admin/categories/edit', ['category' => $category]);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required',
            'description' => 'permit_empty',
        ]);
        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $this->categoriesModel->update($id, [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diupdate.');
    }

    public function delete($id)
    {
        $category = $this->categoriesModel->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('errors', ['Kategori tidak ditemukan.']);
        }
        $this->categoriesModel->delete($id);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus.');
    }

    // Add more methods as needed
}
