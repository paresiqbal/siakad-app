<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDirectory extends Model
{
    protected $table = "staff_directories";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ["name", "position", "department", "email", "phone", "image"];
}
