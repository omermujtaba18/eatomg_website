<?php

namespace App\Models;

use CodeIgniter\Model;

class BusinessModel extends Model
{
    protected $table = 'business';
    protected $primaryKey = 'business_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'business_name',
        'business_address',
        'business_contact',
        'business_contact',
        'business_logo',
        'business_website',
        'business_url_facebook',
        'business_url_instagram',
        'business_url_twitter',
    ];
}
