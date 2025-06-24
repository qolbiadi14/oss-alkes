<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreModel extends Model
{
    protected $table = 'store_profiles';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'name',
        'address',
        'city_id',
        'phone',
        'status',
        'created_at',
        'updated_at',
    ];
}
