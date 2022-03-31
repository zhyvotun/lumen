<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $key = explode(' ', $request->header('Authorization'));
                $user = User::whereHas('accessToken', function (Builder $query) use ($key) {
                    $query->where('api_key', $key[1]);
                })->first();
                if (!empty($user)) {
                    $request->request->add(['userId' => $user->id]);
                }
                return $user;
            }
        });
    }
}
