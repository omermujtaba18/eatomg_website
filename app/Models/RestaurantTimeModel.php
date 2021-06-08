<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantTimeModel extends Model
{
    protected $table = 'restaurant_time';
    protected $primaryKey = 'restaurant_time_id';
    protected $returnType     = 'array';
    protected $allowedFields = [
        'rest_id',
        'day',
        'start_time',
        'end_time',
        'is_closed',
        'is_24h_open'
    ];
}
