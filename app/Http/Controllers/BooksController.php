<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BookResource;
use App\Http\Requests\PostBookRequest;

class BooksController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $option = [
            'per_page' => $request->per_page ?: 15,
            'page' => $request->page ?: 1,
            'title' => $request->title,
            'authors' => $request->authors ? explode(',', $request->authors) : [],
        ];

        $baseQuery = Book::with(['authors', 'reviews'])
        ->select('books.*')
        ->addSelect(DB::raw('(SELECT AVG(review) FROM book_reviews  WHERE book_id = books.id) as avg_review'));

        $query = (clone $baseQuery);

        if ($request->sortColumn) {
            $query->orderBy($request->sortColumn, $request->sortDirection ?: 'ASC');
        }

        if ($option['title']) {
            $query->where('title', 'like', '%' . $option['title'] . '%');
        }
        
        if (count($option['authors'])) {
            $query->whereHas('authors', function ($query) use ($option) {
                $query->whereIn('id', $option['authors']);
            });
        }

        return BookResource::collection($query->paginate($option['per_page']));
    }

    public function store(PostBookRequest $request)
    {
        // Re-validate request only for 'isbn' field
        $request->validate([
            'isbn' => 'digits:13|',
        ]);
        
        $book = Book::create($request->all());
        $book->authors()->attach($request->authors);

        return new BookResource($book);
    }
}
