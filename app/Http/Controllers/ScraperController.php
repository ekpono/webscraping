<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    public function index()
    {
        return BookResource::collection(Book::paginate(6));
    }

    public function destroy($id)
    {
        Book::destroy($id);

        return response()->json([], 204);
    }

    public function edit($id)
    {
        $book = Book::find($id);

        return response()->json([
           'data' => $book,
           'message' => 'Successfully fetched'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'unique:books,title'
        ]);

        $book = Book::find($id);
        $response = tap($book)->update($request->all());

        return response()->json([
           'data' => $response,
           'message' => 'Successfully fetched'
        ]);
    }
}
