<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
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
            'per_page' => $request->per_page ?: 5,
            'page' => $request->page ?: 1,
            'title' => $request->title,
            'authors' => $request->authors ? explode(',', $request->authors) : [],
        ];

        $baseQuery = Book::with(['authors', 'reviews']);

        $query = (clone $baseQuery);

        if ($request->sortColumn && $request->sortDirection) {
            $query->orderBy($request->sortColumn, $request->sortDirection);
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
        // @TODO implement
        $book = new Book();

        return new BookResource($book);
    }
}
