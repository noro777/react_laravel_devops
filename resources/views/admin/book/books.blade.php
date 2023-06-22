@extends('admin.layout')
@section('admin')

<body>
  <div class="container">
    <div class="containrrrr">
      <div>
        <a href="{{ route('admin.home') }}" class="btn btn-primary"> Back </a>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary"> Cteate </a>
      </div>

      <form action="{{ route('admin.books.search') }}" method="POST">
        @csrf
        <input type="search" placeholder="Search" name="search" required value="{{ old('search') }}" aria-label="Search" aria-describedby="search-addon" />
        <span id="search-addon">
          <button type="submit"><i class="fas fa-search"> </i></button>

        </span>
      </form>
    </div>



    <div class="row">
      <div class="col-12">
        @if($books->isNotEmpty())
        <table class="table table-image">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col"> Name</th>
              <th scope="col"> Image</th>
              <th scope="col"> Text</th>
              <th scope="col"> Author Name</th>
              <th scope="col"> Update</th>
              <th scope="col"> Delete</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($books as $key => $book)
            <tr>
              <th scope="row">{{ $key + 1 }}</th>
              <td>{{ $book->name }}</td>
              <td class="w-25">
                <img src="{{ $s3->url('images/books/'.$book->image) }}" class="img-fluid img-thumbnail w-50" alt="Sheep">
              </td>
              <td>{{ $book->text }}</td>
              <td>{{ $book->author->name }}</td>
              <td><a href="{{ route('admin.books.edit',['book'=>$book->id]) }}">update</a></td>
              <td>
                <form method="POST" action="{{ route('admin.books.destroy',['book'=>$book->id]) }}">
                  @csrf
                  <button type="submit">delete</button>
                  @method("delete")
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        @else
        <div>
          <h2>No posts found</h2>
        </div>
        @endif
      </div>
    </div>
  </div>
</body>

@endsection
