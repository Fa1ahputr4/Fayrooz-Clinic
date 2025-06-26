<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaApi extends Model
{
    protected $fillable = ['nama', 'base_url', 'token', 'active'];
}
