<?php

namespace App\Models;

use CodeIgniter\Model;

class ModifierGroupModel extends Model
{
    protected $table = 'modifier_group';
    protected $primaryKey = 'modifier_group_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'modifier_group_name',
        'modifier_group_instruct',
    ];
}
