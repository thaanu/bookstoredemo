<form action="{{ cpanelPermalink('books/ajax/add-new-book') }}" method="post" id="a-book-form" >
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Book Title',
        'name' => 'book_title',
        'id' => 'book_title'
    ])
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Author Name',
        'name' => 'book_author',
        'id' => 'book_author'
    ])
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Published Year',
        'name' => 'published_year',
        'id' => 'published_year'
    ])
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Book Genre',
        'name' => 'book_genre',
        'id' => 'book_genre'
    ])
    @include('admin-panel.components.form.submit', [
        'label' => 'Add Book',
        'name' => 'submit',
        'id' => 'submit'
    ])
</form>