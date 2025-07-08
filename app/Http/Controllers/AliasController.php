<?php

namespace App\Http\Controllers;

use App\Http\Requests\AliasRequest;
use App\Http\Resources\AliasResource;
use App\Models\Alias;

class AliasController extends Controller
{
    public function index()
    {
        return AliasResource::collection(Alias::all());
    }

    public function store(AliasRequest $request)
    {
        return new AliasResource(Alias::create($request->validated()));
    }

    public function show(Alias $alias)
    {
        return new AliasResource($alias);
    }

    public function update(AliasRequest $request, Alias $alias)
    {
        $alias->update($request->validated());

        return new AliasResource($alias);
    }

    public function destroy(Alias $alias)
    {
        $alias->delete();

        return response()->json();
    }
}
