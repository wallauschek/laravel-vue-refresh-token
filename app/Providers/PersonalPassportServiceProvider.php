<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\PassportServiceProvider;
use League\OAuth2\Server\Grant\PasswordGrant;
use Laravel\Passport\Passport;


class PersonalPassportServiceProvider extends PassportServiceProvider
{
    protected function makePasswordGrant()
    {
        $grant = new PasswordGrant(
            $this->app->make(\App\Http\Repositories\UserRepository::class),
            $this->app->make(\Laravel\Passport\Bridge\RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
