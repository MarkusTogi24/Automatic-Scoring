<?php

namespace App\Http\Controllers\Admin;

use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::select("classroom.*", "users.name as teacher")
            ->leftJoin('users', function ($join) {
                $join->on('classroom.teacher_id', 'users.id');
            })   
            ->paginate(10);

        return view('pages.admin.classroom.index', compact('classrooms'));
    }
}
