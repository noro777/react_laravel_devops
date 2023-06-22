<?php

namespace App\Service\Admin;

use App\Models\Book;
use App\Models\Author;
use App\DTO\Admin\GetData;
use Illuminate\Support\Facades\File;
use App\Interfaces\Admin\AdminInterface;
use Storage;

class BookService implements AdminInterface
{
    public function index()
    {
        $books = Book::query()->with('comments')->with('author')->latest()->get();
        $s3 = \Storage::disk('s3');

        return view('admin.book.books', compact('books','s3'));
    }

    public function create()
    {
        $authors = Author::author()->get();

        return view('admin.book.book_create', compact('authors'));
    }


    public function store(GetData $data)
    {
        $s3 = \Storage::disk('s3');
        $imageName   = time() . '.' . $data->image->getClientOriginalExtension();
        $s3->put('images/books/' . $imageName,file_get_contents($data->image->getPathName()),'public');

        $fileName = time() . '.' . $data->file->getClientOriginalExtension();
        $s3->put('files/books/' . $fileName,file_get_contents($data->file->getPathName()),'public');
        $book = new Book();

        $book->name = $data->name;
        $book->image = $imageName;
        $book->text = $data->text;
        $book->file = $fileName;
        $book->author_id = $data->author_id;

        $book->save();
    }

    public function edit(int $id)
    {
        $book = Book::query()->find($id);
        $authors = Author::all();

        return view('admin.book.book_update', compact('book', 'authors'));
    }


    public function update(GetData $data, int $id)
    {
        $s3 = \Storage::disk('s3');
        $book = Book::query()->find($id);

        if ($s3->has('images/books/' . $book->image)) {
            $s3->delete('images/books/' . $book->image);
        }

        if ($s3->has('files/books/' . $book->file)) {
            $s3->delete('files/books/' . $book->file);
        }


        $imageName   = time() . '.' . $data->image->getClientOriginalExtension();
        $s3->put('images/books/' . $imageName,file_get_contents($data->image->getPathName()),'public');

        $fileName = time() . '.' . $data->file->extension();
        $s3->put('files/books/' . $fileName,file_get_contents($data->file->getPathName()),'public');

        $book->name = $data->name;
        $book->image = $imageName;
        $book->text = $data->text;
        $book->file = $fileName;
        $book->author_id = $data->author_id;

        $book->save();
    }

    public function destroy(int $id)
    {
        $s3 = \Storage::disk('s3');
        $book = Book::query()->find($id);

        if ($s3->has('images/books/' . $book->image)) {
            $s3->delete('images/books/' . $book->image);
        }

        if ($s3->has('files/books/' . $book->file)) {
            $s3->delete('files/books/' . $book->file);
        }

        $book->delete();

        return redirect()->route('admin.books.index');
    }

    public function search(GetData $data)
    {
        $s3 = \Storage::disk('s3');
        $books = Book::query()->with('comments')->with('author')
            ->where('name', 'LIKE', '%' . $data->search . '%')
            ->orWhere('text', 'LIKE', '%' . $data->search . '%')
            ->latest()
            ->get();
        
        return view('admin.book.books', compact('books','s3'));
    }
}
