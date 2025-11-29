<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function equipment(){
        return $this->belongsTo(Equipment::class);
    }
    public function getIsOverdueAttribute(){
        if($this->status === 'returned'){
            return false;
        }

        return Carbon::now()->gt(Carbon::parse($this->expected_return_date));
    }

}
