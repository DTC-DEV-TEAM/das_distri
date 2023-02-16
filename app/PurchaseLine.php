<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseLine extends Model
{
    protected $fillable = [
        'digits_code',
        'upc_code',
        'supplier_itemcode',
        'item_description',
        'dtp_rf',
        'skustatus_id',
        'purchase_line_amount',
        'quantity_pre_ordered'
        // 'quantity_ordered',
        // 'quantity_onhand',
        // 'quantity_reservable',
        // 'quantity_reorder',
        // 'quantity_reserved',
        // 'quantity_received'
    ];
}
