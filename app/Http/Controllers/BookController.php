<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Fungsi untuk menangani respons jika data tidak ditemukan.
     *
     * @param string|null $message Pesan kustom untuk respons.
     * @return HttpResponseException
     */
    private function notFoundResponse(string $message = null): HttpResponseException
    {
        throw new HttpResponseException(response()->json([
            'errors' => [
                'message' => [
                    ($message) ?? 'not found'
                ]
            ]
        ], 404));
    }

    /**
     * Fungsi untuk mendapatkan kategori berdasarkan id kategori.
     *
     * @param User $user Pengguna terautentikasi.
     * @param int $idCategory Id kategori yang dicari.
     * @return Category Kategori yang ditemukan.
     */
    private function getCategory(User $user, int $idCategory): Category
    {
        $category = Category::where('user_id', $user->id)->where('id', $idCategory)->first();
        if (!$category) {
            $this->notFoundResponse('category not found');
        }
        return $category;
    }

    /**
     * Fungsi untuk mendapatkan buku berdasarkan id buku.
     *
     * @param Category $category Kategori tempat buku berada.
     * @param int $idBook Id buku yang dicari.
     * @return Book Buku yang ditemukan.
     */
    private function getBook(Category $category, int $idBook): Book
    {
        $book = Book::where('category_id', $category->id)->where('id', $idBook)->first();
        if (!$book) {
            $this->notFoundResponse('book not found');
        }
        return $book;
    }

    /**
     * Fungsi untuk membuat buku baru dalam kategori tertentu.
     *
     * @param int $idCategory Id kategori tempat buku akan dibuat.
     * @param BookCreateRequest $request Request validasi untuk data buku.
     * @return JsonResponse Respons JSON dengan status 201 (Created).
     */
    public function create(int $idCategory, BookCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $category = $this->getCategory($user, $idCategory);

        $data = $request->validated();
        $book = new Book($data);
        $book->user_id = $user->id;
        $book->category_id = $category->id;

        return (new BookResource($book))->response()->setStatusCode(201);
    }

    /**
     * Fungsi untuk mencari buku berdasarkan kriteria:
     * title, publication_year, author, publisher, shell_code
     *
     * @param Request $request Request yang berisi kriteria pencarian.
     * @return JsonResponse Respons JSON dengan daftar buku yang sesuai.
     */
    public function search(Request $request): JsonResponse
    {
        $user = Auth::user();
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $books = Book::where('user_id', $user->id);

        $books = $books->where(function (Builder $builder) use ($request) {
            $title = $request->input('title');
            if ($title) {
                $builder->where('title', 'like', '%' . $title . '%');
            }

            $publication_year = $request->input('publication_year');
            if ($publication_year) {
                $builder->where('publication_year', 'like', '%' . $publication_year . '%');
            }

            $author = $request->input('author');
            if ($author) {
                $builder->where('author', 'like', '%' . $author . '%');
            }

            $publisher = $request->input('publisher');
            if ($publisher) {
                $builder->where('publisher', 'like', '%' . $publisher . '%');
            }

            $shell_code = $request->input('shell_code');
            if ($shell_code) {
                $builder->where('shell_code', 'like', '%' . $shell_code . '%');
            }
        });

        $books = $books->paginate(perPage: $size, page: $page);

        return (BookResource::collection($books))->response()->setStatusCode(200);
    }

    /**
     * Fungsi untuk mendapatkan daftar buku dalam suatu kategori.
     *
     * @param int $idCategory Id kategori yang dicari.
     * @return JsonResponse Respons JSON dengan daftar buku dalam kategori.
     */
    public function getByCategory(int $idCategory): JsonResponse
    {
        $user = Auth::user();

        $category = $this->getCategory($user, $idCategory);
        $books = Book::where('category_id', $category->id)->get();

        return (BookResource::collection($books))->response()->setStatusCode(200);
    }


    /**
     * Fungsi untuk memperbarui informasi buku.
     *
     * @param int $idCategory Id kategori tempat buku berada.
     * @param int $id Id buku yang akan diperbarui.
     * @param BookUpdateRequest $request Request validasi untuk data buku yang akan diperbarui.
     * @return BookResource Sumber daya buku yang telah diperbarui.
     */
    public function update(int $idCategory, int $id, BookUpdateRequest $request): BookResource
    {
        $user = Auth::user();
        $category = $this->getCategory($user, $idCategory);
        $book = $this->getBook($category, $id);

        $data = $request->validated();
        $book->fill($data);
        $book->save();

        return new BookResource($book);
    }

    /**
     * Fungsi untuk mendapatkan informasi buku berdasarkan id buku.
     *
     * @param int $idCategory Id kategori tempat buku berada.
     * @param int $id Id buku yang dicari.
     * @return BookResource Sumber daya buku yang ditemukan.
     */
    public function get(int $idCategory, int $id): BookResource
    {
        $user = Auth::user();
        $category = $this->getCategory($user, $idCategory);
        $book = $this->getBook($category, $id);

        return new BookResource($book);
    }

    /**
     * Fungsi untuk menghapus buku berdasarkan id buku.
     *
     * @param int $idCategory Id kategori tempat buku berada.
     * @param int $id Id buku yang akan dihapus.
     * @return JsonResponse Respons JSON dengan status 200 (OK) jika berhasil dihapus.
     */
    public function delete(int $idCategory, int $id): JsonResponse
    {
        $user = Auth::user();
        $category = $this->getCategory($user, $idCategory);
        $book = $this->getBook($category, $id);

        $book->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
