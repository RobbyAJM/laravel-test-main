<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use Illuminate\Http\Request;
use App\Http\Resources\BookReviewResource;
use App\Http\Requests\PostBookReviewRequest;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        $book = Book::findOrFail($bookId);

        $bookReview = new BookReview();
        $bookReview->book_id = $book->id;
        $bookReview->user_id = auth()->id();
        $bookReview->review = $request->review;
        $bookReview->comment = $request->comment;
        $bookReview->save();

        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        $book = Book::findOrFail($bookId);
        $bookReview = $book->reviews()->findOrFail($reviewId);
        $bookReview->delete();
        return response()->json([], 204);
    }
}
