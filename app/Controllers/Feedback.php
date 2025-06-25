<?php

namespace App\Controllers;

use App\Models\FeedbackModel;
use App\Models\OrderModel;
use App\Models\ProductsModel;
use App\Models\OrderItemsModel;

class Feedback extends BaseController
{
    protected $feedbackModel;
    protected $orderModel;
    protected $productsModel;
    protected $orderItemsModel;

    public function __construct()
    {
        $this->feedbackModel = new FeedbackModel();
        $this->orderModel = new OrderModel();
        $this->productsModel = new ProductsModel();
        $this->orderItemsModel = new OrderItemsModel();
    }

    public function create($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Order tidak ditemukan');
        }

        $products = $this->productsModel->where('store_id', $order['store_id'])->findAll();
        $orderItems = $this->orderItemsModel->where('order_id', $id)->findAll();

        // Cek apakah semua produk pada order sudah diulas
        $feedbackedProductIds = $this->feedbackModel->where('order_id', $id)->findColumn('product_id') ?? [];
        $allProductIds = array_column($orderItems, 'product_id');
        $allReviewed = count($allProductIds) > 0 && count(array_diff($allProductIds, $feedbackedProductIds)) === 0;

        return view('order_reports/feedback', [
            'order' => $order,
            'products' => $products,
            'orderItems' => $orderItems,
            'allReviewed' => $allReviewed
        ]);
    }

    public function store()
    {
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Request method harus POST!');
        }
        log_message('debug', 'Memasuki fungsi store Feedback');

        $validation =  \Config\Services::validation();
        $validation->setRules([
            'user_id' => 'required|numeric',
            'order_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'message' => 'required|string',
            'rating' => 'required|numeric|greater_than[0]|less_than_equal_to[5]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $validation->getErrors()));
        }

        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'order_id' => $this->request->getPost('order_id'),
            'product_id' => $this->request->getPost('product_id'),
            'message' => $this->request->getPost('message'),
            'rating' => $this->request->getPost('rating'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Debug: log data yang akan di-insert
        log_message('debug', 'Feedback data: ' . json_encode($data));

        if ($this->feedbackModel->insert($data)) {
            return redirect()->to('/reports')->with('success', 'Ulasan berhasil disimpan');
        } else {
            // Tampilkan pesan error dari model
            $error = $this->feedbackModel->errors();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan ulasan: ' . json_encode($error));
        }
    }
}
