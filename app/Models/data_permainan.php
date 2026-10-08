<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class data_permainan extends Model
{
    protected $guarded = ['id'];


    public function pembuat():BelongsTo
    {
        return $this->belongsTo(User::class,'pembuat_id');
    }
}
