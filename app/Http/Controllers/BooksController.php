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

        $query = (clone $baseQuery)->offset($option['per_page'] * ($option['page'] - 1))->limit($option['per_page']);

        
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

        $count = $query->count();
        $total_page = ceil($count / $option['per_page']);
        
        $query = $query->get();
        
        return BookResource::collection($query)->additional([
            'links' => [
                'first'     => route('books.index', ['page' => 1]),
                'last'      => route('books.index', ['page' => $total_page]),
                'prev'      => $option['page'] <= 1 ? null : route('books.index', ['page' => $option['page'] - 1]),
                'next'      => $option['page'] >= $total_page ? null : route('books.index', ['page' => $option['page'] + 1]),
            ],
            'meta' => [
                'per_page'      => $option['per_page'],
                'current_page'  => $option['page'],
                'last_page'     => $total_page,
                'from'          => $option['per_page'] * ($option['page'] - 1) + 1,
                'to'            => $option['per_page'] * ($option['page'] - 1) + $count,
                'total'         => $count,
                'path'          => route('books.index'),
            ]
        ]);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $book = new Book();

        return new BookResource($book);
    }
}
