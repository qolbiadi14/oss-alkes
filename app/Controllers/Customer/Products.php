<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\ProductsModel;
use App\Models\StoreModel;
use App\Models\CategoriesModel;

class Products extends BaseController
{
    public function detail($id)
    {
        $productsModel = new ProductsModel();
        $storeModel = new StoreModel();
        $categoriesModel = new CategoriesModel();

        $product = $productsModel->find($id);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan');
        }
        $store = $storeModel->find($product['store_id']);
        $category = $categoriesModel->find($product['category_id']);

        // Dummy feedback & rating, replace with real query if available
        $feedbacks = [
            ['user' => 'User1', 'comment' => 'Produk bagus!', 'rating' => 5],
            ['user' => 'User2', 'comment' => 'Sesuai deskripsi.', 'rating' => 4],
        ];
        $avgRating = count($feedbacks) ? array_sum(array_column($feedbacks, 'rating')) / count($feedbacks) : 0;

        return view('customer/product_detail', [
            'product' => $product,
            'store' => $store,
            'category' => $category,
            'feedbacks' => $feedbacks,
            'avgRating' => $avgRating,
        ]);
    }
}
