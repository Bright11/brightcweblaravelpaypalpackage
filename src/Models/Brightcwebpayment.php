<?php

namespace Brightcweb\Paypal\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Brightcwebpayment extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
