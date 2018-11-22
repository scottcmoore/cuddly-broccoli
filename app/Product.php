<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Allow finding by sku, rather than default 'id'.
    public $primaryKey = 'sku';
    
    
    /**
     * Allow routing by sku, rather than default 'id'.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'sku';
    }
}
