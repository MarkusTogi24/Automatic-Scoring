<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\ClassroomComposer;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }
    
    public function boot()
    {
        View::composer(
            [
                'pages.user.classroom.index',
                'pages.user.classroom.show', 
                'pages.user.exam.index',
                'pages.user.profile.index',
                'pages.user.profile.edit',
            ],
            ClassroomComposer::class
        );

        config(['app.locale' => 'id']);
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }
}
