<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'item_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'item_name',
        'item_desc',
        'item_price',
        'item_pic',
        'item_status',
        'category_id',
        'item_slug'
    ];
}
