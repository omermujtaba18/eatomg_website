<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'order_num',
        'cus_id',
        'order_placed_time',
        'order_delivery_time',
        'order_discount',
        'order_subtotal',
        'order_tax',
        'order_total',
        'order_status',
        'order_type',
        'order_instruct',
        'rest_id',
        'order_complete'
    ];
}
