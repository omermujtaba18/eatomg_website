<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemAddonModel extends Model
{
    protected $table = 'item_addon';
    protected $primaryKey = 'item_addon_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'item_id',
        'addon_group_id'
    ];
}
