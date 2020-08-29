<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemAddonModel extends Model
{
    protected $table = 'order_item_addons';
    protected $primaryKey = 'order_item_addon_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'order_item_id',
        'addon_group_id',
        'addon_id'
    ];
}
