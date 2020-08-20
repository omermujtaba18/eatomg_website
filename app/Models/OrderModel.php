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
        'placed_at',
        'deliver_at',
        'order_discount',
        'order_subtotal',
        'order_tax',
        'order_total',
        'order_status',
        'order_type',
        'order_instruct',
        'rest_id',
        'is_complete',
        'order_payment_type'
    ];
}
