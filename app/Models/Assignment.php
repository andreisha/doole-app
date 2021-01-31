<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model as Model;

class Assignment extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'patient_id',
        'department_id',
        'created_at'
    ];
}
