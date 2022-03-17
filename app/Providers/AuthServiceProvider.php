<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->headers->get('api-key')) {
                try {
                    /** @var Request $request */
                    $user = User::whereId($request->route('id'))->firstOrFail();
                } catch (ModelNotFoundException $exception) {
                    return null;
                }

                return Hash::check($user->auth->api_key, $request->headers->get('api-key')) ? $user : null;
            }
        });
    }
}
