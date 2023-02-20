<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnsHeaderDISTRI extends Model
{
    protected $table = 'returns_header_distribution';
    protected $fillable = ['returns_status', 'returns_status_1'];
}
