<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use App\Http\Requests\FormRequest;

class FormRequestServiceProvider extends ServiceProvider
{
    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->app->afterResolving(ValidatesWhenResolved::class, function($resolved) {
            $resolved->validateResolved();
        });

        $this->app->resolving(FormRequest::class, function($request, $app) {
            FormRequest::createFrom($app->request, $request);
        });
    }
}
