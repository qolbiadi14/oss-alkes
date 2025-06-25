<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProductsModel;
use App\Models\StoreModel;
use App\Models\CategoriesModel;

class Cart extends \App\Controllers\BaseController
{
    public function add($productId)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'customer') {
            return redirect()->to('/login');
        }
        $productsModel = new ProductsModel();
        $product = $productsModel->find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }
        $cart = session()->get('cart') ?? [];
        // Cek jika cart sudah ada item dari toko lain
        if (!empty($cart)) {
            $firstProductId = array_key_first($cart);
            $firstProduct = $productsModel->find($firstProductId);
            if ($firstProduct && $firstProduct['store_id'] != $product['store_id']) {
                return redirect()->back()->with('error', 'Cart hanya boleh berisi produk dari satu toko.');
            }
        }
        // Validasi stok cukup
        $qtyInCart = isset($cart[$productId]) ? $cart[$productId]['qty'] : 0;
        if ($product['stock'] < $qtyInCart + 1) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += 1;
        } else {
            $cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => 1,
            ];
        }
        session()->set('cart', $cart);
        // Redirect ke halaman sebelumnya (dashboard atau detail) jika ada referer, jika tidak ke cart
        $referer = $this->request->getServer('HTTP_REFERER');
        if ($referer) {
            return redirect()->to($referer)->with('success', 'Produk berhasil ditambahkan ke cart.');
        }
        return redirect()->to('/customer/cart')->with('success', 'Produk berhasil ditambahkan ke cart.');
    }

    public function updateQuantity($productId)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'customer') {
            return redirect()->to('/login');
        }
        $qty = (int) $this->request->getPost('qty');
        $cart = session()->get('cart') ?? [];
        $productsModel = new ProductsModel();
        $product = $productsModel->find($productId);
        if (isset($cart[$productId])) {
            if ($qty > 0) {
                if ($product && $product['stock'] < $qty) {
                    return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
                }
                $cart[$productId]['qty'] = $qty;
            } else {
                unset($cart[$productId]);
            }
            session()->set('cart', $cart);
        }
        return redirect()->to('/customer/cart');
    }

    public function remove($productId)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'customer') {
            return redirect()->to('/login');
        }
        $cart = session()->get('cart') ?? [];
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->set('cart', $cart);
        }
        return redirect()->to('/customer/cart');
    }

    public function index()
    {
        // Pastikan user sudah login dan memiliki role 'admin'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'customer') {
            return redirect()->to('/login');
        }
        $cart = session()->get('cart') ?? [];
        $productsModel = new ProductsModel();
        $storeModel = new StoreModel();
        $categoriesModel = new CategoriesModel();
        $stores = $storeModel->findAll();
        $storesMap = [];
        foreach ($stores as $store) {
            $storesMap[$store['id']] = $store['name'];
        }
        $categories = $categoriesModel->findAll();
        $categoriesMap = [];
        foreach ($categories as $cat) {
            $categoriesMap[$cat['id']] = $cat['name'];
        }
        return view('customer/cart', [
            'cart' => $cart,
            'storesMap' => $storesMap,
            'categories' => $categories,
            'categoriesMap' => $categoriesMap,
        ]);
    }
}
