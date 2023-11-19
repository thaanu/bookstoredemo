@if ( $books['count'] == 0 )
    <p class="text-center">No Books Found</p>
    <p class="text-center">
        <button type="button" id="new-book-btn" class="btn btn-primary" >New Book</button>
    </p>
@else
    
    <div class="py-2 mb-2">
        <button type="button" id="new-book-btn" class="btn btn-primary" >New Book</button>
    </div>

    <div class="row">
        @foreach ($books['data'] as $book)
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h4>{{ $book['book_title'] }}</h4>
                        <p>by {{ $book['book_author'] }} published on {{ $book['published_year'] }}</p>
                        <p>
                            <button data-url="{{ cpanelPermalink('books/ajax/show-book/'.$book['book_id']) }}" class="edit-btn btn btn-sm btn-light" style="margin-right: 10px;">Edit</button> 
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endif