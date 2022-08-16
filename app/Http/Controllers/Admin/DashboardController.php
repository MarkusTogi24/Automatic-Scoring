<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $students_count = User::query()
            ->where('role', "SISWA")
            ->count();
        $teachers_count = User::query()
            ->where('role', "GURU")
            ->count();
        $classrooms_count = Classroom::count();

        return view('pages.admin.dashboard.index', compact('students_count', 'teachers_count', 'classrooms_count'));
    }
}
