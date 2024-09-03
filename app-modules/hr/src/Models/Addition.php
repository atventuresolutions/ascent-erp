<?php

namespace Modules\Hr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addition extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
