<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'category_name',
        'category_desc',
        'category_slug',
        'category_type',
    ];
}
