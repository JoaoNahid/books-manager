<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {
    protected $fillable = ['log'];

    public static function createLog($log) {
        return self::create(['log' => $log]);
    }
}
