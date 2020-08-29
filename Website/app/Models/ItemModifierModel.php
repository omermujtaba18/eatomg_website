<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModifierModel extends Model
{
    protected $table = 'item_modifier';
    protected $primaryKey = 'item_modifier_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'item_id',
        'modifier_group_id'
    ];
}
