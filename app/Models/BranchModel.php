<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchModel extends Model
{
    use HasFactory;
    protected $table = 'branch';
    protected $fillable = [
        'name',
        'address',
        'phone',
        'created_at',
        'updated_at',
    ];
}
