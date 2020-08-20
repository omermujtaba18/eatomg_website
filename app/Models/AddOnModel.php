<?php

namespace App\Models;

use CodeIgniter\Model;

class AddOnModel extends Model
{
    protected $table = 'addon';
    protected $primaryKey = 'addon_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'addon_item',
        'addon_price',
        'addon_group_id',
    ];
}
