<?php

namespace App\Providers;

use App\Result;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Uuid;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Result::creating(function($model) {
            if(!$model->id)
            {
                $model->id = Uuid::uuid4()->toString();
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
