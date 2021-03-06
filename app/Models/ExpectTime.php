<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpectTime extends Model
{
    protected $table = 'expect_times';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'expect_job_id'];
}
