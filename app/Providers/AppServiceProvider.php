<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TestResponse::macro(
            'assertJsonApiValidationErrors', function($attribute){
                /** @var TestResponse $this */
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail','source' => ['pointer']]
                    ]
                ])->assertJsonFragment([
                    'source' => ['pointer' => "/data/attributes/{$attribute}"]
                ])->assertHeader(
                    'content-type', 'application/vnd.api+json'
                )->assertStatus(422);    
            }
        );
    }
}
