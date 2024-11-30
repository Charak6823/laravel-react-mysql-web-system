<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "dob",
        "gender",
        "tel",
        "email",
        "salary",
        "address_info",
        "province_id",
        "card_id",
        "status"
    ];
}
