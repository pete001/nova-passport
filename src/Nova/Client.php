<?php

namespace Petecheyne\Passport\Nova;

use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Petecheyne\Passport\Nova\Actions\RefreshClientSecret;

class Client extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Laravel\Passport\Client::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('user_id', '<>', null);
    }

    public static function group()
    {
        return __('Passport');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make(__('User'), 'user', User::class)
                ->searchable(),

            Text::make(__('Name'), 'name')
                ->rules([
                    'required',
                    'max:255'
                ]),

            Text::make(__('Redirect URL'), 'redirect_uri')
                ->rules([
                    'required',
                    'url'
                ])->hideFromDetail()->hideFromIndex(),

            Text::make(__('Redirect URL'), 'redirect')
                ->rules([
                    'required',
                    'url'
                ])->hideWhenCreating()->hideWhenUpdating(),

            ID::make(__('Client ID'), 'id')->sortable(),

            Text::make(__('Client Secret'), 'secret')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            HasMany::make(__('Tokens'), 'tokens', Token::class),
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
        return [
            (new Metrics\OauthClients),
            (new Metrics\ConnectedUsersToApps),
            (new Metrics\UsersPerApp),
        ];
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
            (new RefreshClientSecret)->withoutActionEvents(),
        ];
    }
}
