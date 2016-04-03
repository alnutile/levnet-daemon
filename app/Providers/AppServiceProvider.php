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
            if(!$model->tries)
            {
                $model->tries = 1;
            } else {
                $model->tries = $model->tries++;
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
