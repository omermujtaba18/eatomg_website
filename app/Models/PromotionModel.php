<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'promo_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'promo_name',
        'promo_code',
        'promo_type',
        'promo_amount',
        'is_active'
    ];
}
