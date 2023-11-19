<div class="card card-body">
    <h5 class="card-title m-0"><i class="fas fa-book" style="margin-right: 10px;"></i>Books</h5>
    <hr>
    <div class="card-text">

        <div class="accordion custom-accordion" id="custom-accordion-one">
            <div class="card mb-0">
                <div class="card-header" id="h-one">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title text-reset d-block" data-bs-toggle="collapse" href="#col-one">
                            Get list of books <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                <div id="col-one" class="collapse" data-bs-parent="#custom-accordion-one">
                    <div class="card-body">
                        <p><strong>Request URL</strong></p>
                        <p><strong class="text-success">GET</strong> <code>/api/books/all/<strong>{page_no}</strong>/<strong>{limit}</strong></code></p>
                        <p><strong>Parameters</strong></p>
                        <table class="table">
                            <tr>
                                <th><code>page_no</code></th>
                                <td>Set the page number. Example 1, 2, 3...n</td>
                            </tr>
                            <tr>
                                <th><code>limit</code></th>
                                <td>Total number of records per page. Example 1, 2, 3...n</td>
                            </tr>
                        </table>
                        <p><strong>Response</strong></p>
<pre>
    {
        "status": 200,
        "results": {
            "current_page": 1,
            "total_records": null,
            "limit": 10,
            "results": [
                {
                    "book_id": "5",
                    "book_title": "Harry Potter",
                    "book_author": "J. K. Rowling",
                    "published_year": "2010",
                    "book_genre": "Fiction"
                },
                {
                    "book_id": "6",
                    "book_title": "CRUD API Development",
                    "book_author": "Shan",
                    "published_year": "2023",
                    "book_genre": "Real Story"
                },
                {
                    "book_id": "7",
                    "book_title": "Postman 2",
                    "book_author": "Ahmed Shan",
                    "published_year": "2020",
                    "book_genre": "Documentry"
                }
            ]
        },
        "error": null
    }
</pre>
                    </div>
                </div>
            </div>


            {{-- Get a single --}}
            <div class="card mb-0">
                <div class="card-header" id="h-two">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title text-reset d-block" data-bs-toggle="collapse" href="#col-two">
                            Get a single book by the book ID <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                <div id="col-two" class="collapse" data-bs-parent="#custom-accordion-one">
                    <div class="card-body">
                        <p><strong>Request URL</strong></p>
                        <p><strong class="text-success">GET</strong> <code>/api/books/list/<strong>{book_id}</strong></code></p>
                        <p><strong>Parameters</strong></p>
                        <table class="table">
                            <tr>
                                <th><code>book_id</code></th>
                                <td>The unique ID given for the book. Example 1, 2, 3...n</td>
                            </tr>
                        </table>
                        <p><strong>Response</strong></p>
<pre>
{
    "status": 200,
    "results": {
        "current_page": 1,
        "total_records": null,
        "limit": 10,
        "results": [
            {
                "book_id": "5",
                "book_title": "Harry Potter",
                "book_author": "J. K. Rowling",
                "published_year": "2010",
                "book_genre": "Fiction"
            },
            {
                "book_id": "6",
                "book_title": "CRUD API Development",
                "book_author": "Shan",
                "published_year": "2023",
                "book_genre": "Real Story"
            },
            {
                "book_id": "7",
                "book_title": "Postman 2",
                "book_author": "Ahmed Shan",
                "published_year": "2020",
                "book_genre": "Documentry"
            }
        ]
    },
    "error": null
}
</pre>
                    </div>
                </div>
            </div>

            {{-- Create Book --}}
            <div class="card mb-0">
                <div class="card-header" id="h-three">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title text-reset d-block" data-bs-toggle="collapse" href="#col-three">
                            Create new book <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                <div id="col-three" class="collapse" data-bs-parent="#custom-accordion-one">
                    <div class="card-body">
                        <p><strong>Request URL</strong></p>
                        <p><strong class="text-primary">POST</strong> <code>/api/books/create</code></p>
                        <p><strong>Payload</strong></p>
                        <p>Send the payload in JSON format.</p>
                        <table class="table">
                            <tr>
                                <th><code>book_title</code></th>
                                <td>Book title (String)</td>
                            </tr>
                            <tr>
                                <th><code>book_author</code></th>
                                <td>Book Author (String)</td>
                            </tr>
                            <tr>
                                <th><code>published_year</code></th>
                                <td>Book Published Year (Int)</td>
                            </tr>
                            <tr>
                                <th><code>book_genre</code></th>
                                <td>Book Genre (String)</td>
                            </tr>
                        </table>
                        <p><strong>Response</strong></p>
<pre>
{
    "status": 200,
    "error": null,
    "message": "New book added successfully"
}
</pre>
                    </div>
                </div>
            </div>

            {{-- Update Book --}}
            <div class="card mb-0">
                <div class="card-header" id="h-four">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title text-reset d-block" data-bs-toggle="collapse" href="#col-four">
                            Update book information <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                <div id="col-four" class="collapse" data-bs-parent="#custom-accordion-one">
                    <div class="card-body">
                        <p><strong>Request URL</strong></p>
                        <p><strong class="text-warning">PUT</strong> <code>/api/books/update/<strong>{book_id}</strong></code></p>
                        <p><strong>Parameters</strong></p>
                        <table class="table">
                            <tr>
                                <th><code>book_id</code></th>
                                <td>The unique ID given for the book. Example 1, 2, 3...n</td>
                            </tr>
                        </table>
                        <p><strong>Payload</strong></p>
                        <p>Send the payload in JSON format.</p>
                        <table class="table">
                            <tr>
                                <th><code>book_title</code></th>
                                <td>Book title (String)</td>
                            </tr>
                            <tr>
                                <th><code>book_author</code></th>
                                <td>Book Author (String)</td>
                            </tr>
                            <tr>
                                <th><code>published_year</code></th>
                                <td>Book Published Year (Int)</td>
                            </tr>
                            <tr>
                                <th><code>book_genre</code></th>
                                <td>Book Genre (String)</td>
                            </tr>
                        </table>
                        <p><strong>Response</strong></p>
<pre>
{
    "status": 200,
    "error": null,
    "message": "",
    "textMessage": "Book updated successfully"
}
</pre>
                    </div>
                </div>
            </div>

            {{-- Delete Book --}}
            <div class="card mb-0">
                <div class="card-header" id="h-five">
                    <h5 class="m-0 position-relative">
                        <a class="custom-accordion-title text-reset d-block" data-bs-toggle="collapse" href="#col-five">
                            Remove Book <i class="mdi mdi-chevron-down accordion-arrow"></i>
                        </a>
                    </h5>
                </div>
                <div id="col-five" class="collapse" data-bs-parent="#custom-accordion-one">
                    <div class="card-body">
                        <p><strong>Request URL</strong></p>
                        <p><strong class="text-danger">DELETE</strong> <code>/api/books/remove/<strong>{book_id}</strong></code></p>
                        <p><strong>Parameters</strong></p>
                        <table class="table">
                            <tr>
                                <th><code>book_id</code></th>
                                <td>The unique ID given for the book. Example 1, 2, 3...n</td>
                            </tr>
                        </table>
                        <p><strong>Response</strong></p>
<pre>
{
    "status": 200,
    "error": null,
    "message": "",
    "textMessage": "Book removed successfully"
}
</pre>
                    </div>
                </div>
            </div>


        </div>

    </div>
</div>