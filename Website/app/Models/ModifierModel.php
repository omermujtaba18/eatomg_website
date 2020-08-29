<?php

namespace App\Models;

use CodeIgniter\Model;

class ModifierModel extends Model
{
    protected $table = 'modifiers';
    protected $primaryKey = 'modifier_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'modifier_item',
        'modifier_price',
        'modifier_group_id'
    ];
}
