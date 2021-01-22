<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_items_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'order_id',
        'item_id',
        'order_item_quantity',
        'order_item_note'
    ];
}
