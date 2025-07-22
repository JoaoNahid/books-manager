<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;

class BooksController extends Controller
{
    public function index() {
        return Inertia::render('Books/Index', [
            'books' => Book::all()
        ]);
    }
}
