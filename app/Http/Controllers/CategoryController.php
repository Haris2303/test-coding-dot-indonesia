<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function create(CategoryCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $category = new Category($data);
        $category->user_id = $user->id;
        $category->save();

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    public function getAll(): CategoryCollection
    {
        $category = Category::all(['name', 'description']);
        return new CategoryCollection($category);
    }

    public function update(int $id, CategoryUpdateRequest $request): CategoryResource
    {
        $user = Auth::user();

        $category = Category::where('id', $id)->where('user_id', $user->id)->first();
        if (!$category) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $category->fill($data);
        $category->save();

        return new CategoryResource($category);
    }

    public function get(int $id): CategoryResource
    {
        $user = Auth::user();

        $category = Category::where('id', $id)->where('user_id', $user->id)->first();
        if (!$category) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new CategoryResource($category);
    }

    public function delete(int $id): JsonResponse
    {
        $user = Auth::user();

        $category = Category::where('id', $id)->where('user_id', $user->id)->first();
        if (!$category) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $category->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
