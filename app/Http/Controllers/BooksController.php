<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\FileUpload;
use Inertia\Inertia;

class BooksController extends Controller
{
    public function index() {
        return Inertia::render('Books/Index', [
            'books' => Book::getBooks()->paginate(10),
            'authors' => Author::getActiveAuthors(),
        ]);
    }

    public function store(BookRequest $request) {
        Book::post($request->validated());
        return redirect()->route('books.index');
    }

    public function deleteBook($id) {
        $book = Book::findOrFail($id);
        if ($book->image) {
            FileUpload::deleteImage($book->image);
        }
        $book->delete();

        return back()->with('success', 'Livro excluído com sucesso!');
    }
}
