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
    /**
     * Fungsi untuk membuat kategori baru.
     *
     * @param CategoryCreateRequest $request Request validasi untuk data kategori.
     * @return JsonResponse Respons JSON dengan status 201 (Created).
     */
    public function create(CategoryCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $category = new Category($data);
        $category->user_id = $user->id;
        $category->save();

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    /**
     * Fungsi untuk mendapatkan semua kategori.
     *
     * @return CategoryCollection Koleksi kategori.
     */
    public function getAll(): CategoryCollection
    {
        $categories = Category::all();
        return new CategoryCollection($categories);
    }

    /**
     * Fungsi untuk memperbarui informasi kategori.
     *
     * @param int $id Id kategori yang akan diperbarui.
     * @param CategoryUpdateRequest $request Request validasi untuk data kategori yang akan diperbarui.
     * @return CategoryResource Sumber daya kategori yang telah diperbarui.
     */
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

    /**
     * Fungsi untuk mendapatkan informasi kategori berdasarkan id kategori.
     *
     * @param int $id Id kategori yang dicari.
     * @return CategoryResource Sumber daya kategori yang ditemukan.
     */
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

    /**
     * Fungsi untuk menghapus kategori berdasarkan id kategori.
     *
     * @param int $id Id kategori yang akan dihapus.
     * @return JsonResponse Respons JSON dengan status 200 (OK) jika berhasil dihapus.
     */
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
