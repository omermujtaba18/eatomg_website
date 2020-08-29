<?php

namespace App\Models;

use CodeIgniter\Model;

class AddOnGroupModel extends Model
{
    protected $table = 'addon_group';
    protected $primaryKey = 'addon_group_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'addon_group_name',
        'addon_group_instruct',
    ];
}
