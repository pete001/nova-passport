<?php

namespace Petecheyne\Passport\Http\Controllers;

use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\CreateResourceRequest;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\TokenRepository;

class ResourceStoreController extends \Laravel\Nova\Http\Controllers\ResourceStoreController
{
    /**
     * Create a new resource.
     *
     * @param  \Laravel\Nova\Http\Requests\CreateResourceRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(CreateResourceRequest $request)
    {
        $resource = $request->resource();

        if($resource === "Petecheyne\Passport\Nova\Client") {
            $resource::authorizeToCreate($request);

            $resource::validateForCreation($request);

            $model = (new ClientRepository)->create($request->get('user'), $request->get('name'), $request->get('redirect_uri'));

            return response()->json([
                'id' => $model->getKey(),
                'resource' => $model->attributesToArray(),
                'redirect' => $resource::redirectAfterCreate($request, $request->newResourceWith($model)),
            ], 201);
        } elseif($resource === "Petecheyne\Passport\Nova\Token") {
            $resource::authorizeToCreate($request);

            $resource::validateForCreation($request);


            $model = (new TokenRepository)->create([
                'id' => Str::random(40),
                'revoked' => false,
                'user_id' => $request->get('user'),
                'client_id' => $request->get('client'),
                'scopes' => json_decode($request->get('scopes')),
                'created_at' => now(),
            ]);

            return response()->json([
                'id' => $model->getKey(),
                'resource' => $model->attributesToArray(),
                'redirect' => $resource::redirectAfterCreate($request, $request->newResourceWith($model)),
            ], 201);
        } else {
            return parent::handle($request);
        }
    }
}
