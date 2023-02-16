<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnsBodyDISTRI extends Model
{
    protected $table = 'returns_body_item_distribution';
    protected $fillable = ['digits_code','upc_code','problem_details','problem_details_other','line_id'];
}
