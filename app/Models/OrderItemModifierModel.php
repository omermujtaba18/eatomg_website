<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModifierModel extends Model
{
    protected $table = 'order_item_modifiers';
    protected $primaryKey = 'order_item_modifier_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'order_item_id',
        'modifier_group_id',
        'modifier_id'
    ];
}
