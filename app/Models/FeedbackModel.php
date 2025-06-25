<?php

namespace App\Models;
use CodeIgniter\Model;
class FeedbackModel extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'order_id',
        'product_id',
        'message',
        'rating',
        'created_at',
    ];

}
