<?php

namespace App\Service\Admin;

use App\Models\Author;
use App\DTO\Admin\GetData;
use Illuminate\Support\Facades\File;
use App\Interfaces\Admin\AdminInterface;
use Illuminate\Support\Facades\Storage;

class AuthorService implements AdminInterface
{

    public function index()
    {
        $authors = Author::author()->latest()->get();
        $s3 = \Storage::disk('s3');

        return view('admin.author.authors', compact('authors', 's3'));
    }

    public function create()
    {
        return view('admin.author.author_create');
    }


    public function store(GetData $data)
    {
        $s3 = \Storage::disk('s3');
        $imageName   = time() . '.' . $data->image->getClientOriginalExtension();
        $s3->put('images/authors/' . $imageName,file_get_contents($data->image->getPathName()),'public');

        $author = new Author();

        $author->name = $data->name;
        $author->image = $imageName;
        $author->text = $data->text;

        $author->save();
    }

    public function edit(int $id)
    {
        $author = Author::query()->find($id);

        return view('admin.author.author_update', compact('author'));
    }


    public function update(GetData $data, int $id)
    {
        $s3 = \Storage::disk('s3');
        $author = Author::query()->find($id);
        $imageName   = time() . '.' . $data->image->getClientOriginalExtension();
        $s3->put('images/authors/' . $imageName,file_get_contents($data->image->getPathName()),'public');

        if ($s3->has('images/authors/' . $author->image)) {
            $s3->delete('images/authors/' . $author->image);
        }


        $imageName   = time() . '.' . $data->image->getClientOriginalExtension();
        $s3->put('images/authors/' . $imageName,file_get_contents($data->image->getPathName()),'public');

        $author->name = $data->name;
        $author->image = $imageName;
        $author->text = $data->text;

        $author->save();
    }

    public function destroy(int $id)
    {
        $author = Author::query()->find($id);
        $s3 = \Storage::disk('s3');

        if ($s3->has('images/authors/' . $author->image)) {
            $s3->delete('images/authors/' . $author->image);
        }

        $author->delete();

        return redirect()->route('admin.authors.index');
    }

    public function search(GetData $data)
    {
        $authors = Author::author()
            ->where('name', 'LIKE', '%' . $data->search . '%')
            ->orWhere('text', 'LIKE', '%' . $data->search . '%')
            ->latest()
            ->get();
        $s3 = \Storage::disk('s3');

        return view('admin.author.authors', compact('authors','s3'));
        
    }
}