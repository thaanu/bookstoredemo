<?php
/**
 * Books Api Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\ApiController;
use Heliumframework\Validate;
use Heliumframework\Model\Book;

class BooksApiController extends ApiController {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {}

    // Fetch books
    public function all( int $page = 1, int $limit = 10 )
    {
        try {
            $this->response = [
                'status' => 200,
                'results' => (new Book())->selectPaginatedBooks( $page, $limit )
            ];
        }
        catch ( Exception $ex ) {
            $this->setError($ex->getMessage(), $ex->getCode());
        }
        finally {
            $this->sendResponse();
        }
    }

    public function list( int $bookId )
    {
        try {
            $book = (new Book())->selectBookById( $bookId );
            if ( $book['count'] == 0 ) {
                throw new Exception('Unable to find book', 404);
            }
            $this->response = [
                'status' => 200,
                'results' => $book['data']
            ];
        }
        catch ( Exception $ex ) {
            $this->setError($ex->getMessage(), $ex->getCode());
        }
        finally {
            $this->sendResponse();
        }
    }

    public function store()
    {
        try {

            $req = [
                'bookTitle' => [
                    'required' => true,
                    'label' => 'Book Title'
                ],
                'bookAuthor' => [
                    'required' => true,
                    'label' => 'Author Name'
                ],
                'publishedYear' => [
                    'required' => true,
                    'label' => 'Published Year'
                ],
                'bookGenre' => [
                    'required' => true,
                    'label' => 'Book Genre'
                ]
            ];

            $validate = new Validate();
            $validation = $validate->check( (array) $this->post, $req);

            if ( $validation->passed() == false ) {
                $this->response['error_fields'] = $validation->errors();
                throw new Exception('Required fields are missing', 400);
            }

            $book = new Book();
            $book->setPayload('book_title', $this->post->bookTitle);
            $book->setPayload('book_genre', $this->post->bookGenre);
            $book->setPayload('book_author', $this->post->bookAuthor);
            $book->setPayload('published_year', $this->post->publishedYear);

            if ( ! $book->store() ) {
                throw new Exception('Unable to add new book', 500);
            }

            $this->response['status'] = 200;
            $this->response['message'] = 'New book added successfully';

        }
        catch ( Exception $ex ) {
            $this->setError($ex->getMessage(), $ex->getCode());
        }
        finally {
            $this->sendResponse();
        }
    }

    public function update( int $bookId = null )
    {
        try {

            if ( $bookId == null ) {
                throw new Exception('Required book is unavailable', 404);
            }

            // find book
            $theBook = (new Book())->selectBookById($bookId);

            if ( $theBook['count'] == 0 ) {
                throw new Exception('Required book is unavailable', 404);
            }

            $req = [
                'bookTitle' => [
                    'required' => true,
                    'label' => 'Book Title'
                ],
                'bookAuthor' => [
                    'required' => true,
                    'label' => 'Author Name'
                ],
                'publishedYear' => [
                    'required' => true,
                    'label' => 'Published Year'
                ],
                'bookGenre' => [
                    'required' => true,
                    'label' => 'Book Genre'
                ]
            ];

            $validate = new Validate();
            $validation = $validate->check( (array) $this->post, $req);

            if ( $validation->passed() == false ) {
                $this->response['error_fields'] = $validation->errors();
                throw new Exception('Required fields are missing', 400);
            }

            $book = new Book( $bookId );
            $book->setPayload('book_title', $this->post->bookTitle);
            $book->setPayload('book_genre', $this->post->bookGenre);
            $book->setPayload('book_author', $this->post->bookAuthor);
            $book->setPayload('published_year', $this->post->publishedYear);

            if ( ! $book->update() ) {
                throw new Exception('Unable to update book', 500);
            }

            $this->response['status'] = 200;
            $this->response['message'] = 'Book updated successfully';

        }
        catch ( Exception $ex ) {
            $this->setError($ex->getMessage(), $ex->getCode());
        }
        finally {
            $this->sendResponse();
        }
    }

    public function delete( int $bookId = null )
    {
        try {

            if ( $bookId == null ) {
                throw new Exception('Required book is unavailable', 404);
            }

            // find book
            $theBook = (new Book())->selectBookById($bookId);

            if ( $theBook['count'] == 0 ) {
                throw new Exception('Required book is unavailable', 404);
            }

            $book = new Book( $bookId );

            if ( ! $book->removeBook() ) {
                throw new Exception('Unable to remove book', 500);
            }

            $this->response['status'] = 200;
            $this->response['message'] = 'Book removed successfully';

        }
        catch ( Exception $ex ) {
            $this->setError($ex->getMessage(), $ex->getCode());
        }
        finally {
            $this->sendResponse();
        }
    }

    private function authField( $field )
    {
        if ( ! property_exists($this->post, $field) ) {
            throw new Exception("$field is required", 400);
        }
    }

}