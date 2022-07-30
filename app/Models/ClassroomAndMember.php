<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomAndMember extends Model
{
    use HasFactory;

    protected $table = 'classroom_and_member';

    protected $fillable = [
        'classroom_id',
        'member_id',
    ];
}
