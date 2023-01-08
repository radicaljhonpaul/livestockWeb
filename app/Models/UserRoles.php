<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UserRoles extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'user_roles';

    protected $primaryKey = 'id';

    protected $fillable = ['role', 'user_id'];
}
