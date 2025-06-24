<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'store_id',
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'created_at',
        'updated_at',
        'image',
    ];
}
