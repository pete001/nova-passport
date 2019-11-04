<?php

namespace Petecheyne\Passport\Nova\Actions;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Passport\Passport;

class RefreshClientSecret extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $expiration = Carbon::now()->addMinutes(config('session.lifetime'))->format('Y-m-d H:i:s');

        foreach ($models as $model) {

            $model->tokens->each(function ($token) use ($expiration){
                Passport::refreshToken()->where('id', $token->id)->update([
                    'expires_at' => $expiration
                ]);
            });
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
