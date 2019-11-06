<?php

namespace Petecheyne\Passport\Http\Controllers;

use Laravel\Nova\Http\Requests\CreateResourceRequest;
use Laravel\Passport\ClientRepository;

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

            $model = (new ClientRepository)->create($request->get('user'), $request->get('name'), $request->get('redirect'));

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
