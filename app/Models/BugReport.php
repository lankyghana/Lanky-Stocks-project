<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'status', 'screenshot'
    ];
}
