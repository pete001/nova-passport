<?php

namespace Petecheyne\Passport\Nova;

use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Timezone;
use Petecheyne\Passport\Nova\Actions\RevokeAccess;

class Token extends Resource
{
    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Laravel\Passport\Token::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('ID', 'id')
                ->hideWhenUpdating()
                ->hideWhenCreating()
                ->hideFromIndex(),

            BelongsTo::make(__('User'), 'user', User::class),

            BelongsTo::make(__('Client'), 'client', Client::class),

            Text::make(__('Scopes'), 'scopes'),

            Boolean::make(__('Revoked'), 'revoked')
                ->hideWhenUpdating()
                ->hideWhenCreating(),

            Timezone::make(__('Created At'), 'created_at')
                ->hideWhenUpdating()
                ->hideWhenCreating()
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new RevokeAccess)->withoutActionEvents(),
        ];
    }
}
