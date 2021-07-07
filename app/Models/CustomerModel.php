<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'cus_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'cus_name',
        'cus_email',
        'cus_password',
        'cus_phone',
        'cus_address',
        'cus_city',
        'cus_state',
        'cus_country',
        'cus_zip',
        'cus_dob',
        'has_register',
        'token',
        'business_id',
        'rest_id'
    ];
}
