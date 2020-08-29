<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'user_name',
        'user_email',
        'user_password',
        'user_role',
        'user_rest'
    ];
}
