<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferenceCounter extends Model
{
    protected $table = 'reference_counters';

    public function incrementCounter($counter_name){

        $data = $this->where('name', $counter_name)->first();

        if($data){

            $data->increment('value');

            return $data->value;
        }else{
            return 'Counter not found';
        }
    }
}
