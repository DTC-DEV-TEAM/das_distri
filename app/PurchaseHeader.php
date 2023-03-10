<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseHeader extends Model
{
    protected $fillable = [
        'suppliers_id',
        'comments',
        'order_date',
        'po_reference',
        'groups_id',
        'channels_id',
        'stores_id',
        'total_amount',
        'excel_file'
    ];
}
