@if ( $book['count'] == 0 )
    <p>Requested book is unavailable</p>
@else
    <form action="{{ cpanelPermalink('books/ajax/update-book/'.$book['data']['book_id']) }}" method="post" id="a-book-form" >
        @include('admin-panel.components.form.input', [
            'type' => 'text',
            'label' => 'Book Title',
            'name' => 'book_title',
            'id' => 'book_title',
            'value' => $book['data']['book_title']
        ])
        @include('admin-panel.components.form.input', [
            'type' => 'text',
            'label' => 'Author Name',
            'name' => 'book_author',
            'id' => 'book_author',
            'value' => $book['data']['book_author']
        ])
        @include('admin-panel.components.form.input', [
            'type' => 'text',
            'label' => 'Published Year',
            'name' => 'published_year',
            'id' => 'published_year',
            'value' => $book['data']['published_year']
        ])
        @include('admin-panel.components.form.input', [
            'type' => 'text',
            'label' => 'Book Genre',
            'name' => 'book_genre',
            'id' => 'book_genre',
            'value' => $book['data']['book_genre']
        ])
        @include('admin-panel.components.form.submit', [
            'label' => 'Update Book',
            'name' => 'submit',
            'id' => 'submit'
        ])
    </form>

    <hr>

    <p class="text-danger">Remove Book</p>
    <p>Once if you remove a book, you cannot restore them.</p>
    <p><button data-url="{{ cpanelPermalink('books/ajax/remove-book/'.$book['data']['book_id']) }}" id="remove-btn" class="btn btn-sm btn-danger">Remove Book</button> </p>

@endif