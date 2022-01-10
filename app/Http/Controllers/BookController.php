<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Returns the list of books.
     *
     * @return Illuminate/Http/Response
     */
    public function index(){
        $books = Book::all();

        return $this->successResponse($books);
    }

    /**
     * Create one new book.
     *
     * @return Illuminate/Http/Response
     */
    public function store(Request $request){
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'author_id' => 'required|integer|min:1',
        ];

        $this->validate($request, $rules);

        $book = Book::create($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);
    }

    /**
     * Obtains and shows a book.
     *
     * @return Illuminate/Http/Response
     */
    public function show($book){
        $book = Book::findOrFail($book);

        return $this->successResponse($book);
    }

    /**
     * Updates an existing book
     *
     * @return Illuminate/Http/Response
     */
    public function update(Request $request, $book){
        $rules = [
            'title' => 'string|max:255',
            'description' => 'string|max:255',
            'price' => 'integer|min:1',
            'author_id' => 'integer|min:1',
        ];

        $this->validate($request, $rules);

        $book = Book::findOrFail($book);

        $book->fill($request->all());

        if($book->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book->save();

        return $this->successResponse($book);
    }

    /**
     * Removes and existing book
     *
     * @return Illuminate/Http/Response
     */
    public function destroy($book){
        $book = Book::findOrFail($book);
        $book->delete();

        return $this->successResponse($book);
    }
}
