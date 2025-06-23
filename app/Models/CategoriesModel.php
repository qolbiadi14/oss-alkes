<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriesModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name','description','created_at','updated_at'];
}
