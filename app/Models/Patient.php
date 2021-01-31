<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'firstName',
        'lastName',
        'created_at'
    ];
}
