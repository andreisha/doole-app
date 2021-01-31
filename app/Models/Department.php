<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'created_at'
    ];
}
