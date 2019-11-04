<?php

namespace Petecheyne\Passport\Nova\Metrics;

use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;
use Laravel\Passport\Token;

class UsersPerApp extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, Token::join('oauth_clients', 'oauth_clients.id', '=', 'oauth_access_tokens.client_id')
            ->where('oauth_clients.user_id', '<>', null), 'oauth_clients.name');
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'users-per-app';
    }
}
