<?php

namespace App\Models;
use CodeIgniter\Model;
class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at'; 
    protected $allowedFields = [
        'order_number',
        'store_id',
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_token',
        'payment_gateway',
        'payment_status',
        'paid_at',
        'created_at',
        'updated_at'
    ];

}