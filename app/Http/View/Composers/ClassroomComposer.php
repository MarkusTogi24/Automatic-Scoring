<?php

namespace App\Http\View\Composers;

use App\Models\Classroom;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ClassroomComposer
{
    public function compose(View $view)
    {
        $view->with('user_classrooms', 
        Classroom::query()
            ->leftJoin('classroom_and_member', 'classroom.id', '=', 'classroom_and_member.classroom_id')
            ->where('classroom_and_member.member_id', Auth::user()->id)
            ->get()
        );
    }
}