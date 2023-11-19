<?php
/**
 * Books Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Validate;
use Heliumframework\Model\Book;
use Heliumframework\Model\Location;

class BooksController extends Controller {

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.books.main');
    }

    public function ajaxHandler( $action = '', $param = '' )
    {
        try {

            // Fetch all books
            if ( $action == 'fetch-books' ) {
                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.books._data', [
                    'books' => (new Book())->selectAll()
                ]);
            }
            // Show new book form
            else if ( $action == 'new-book-form' ) {
                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.books._create');
            }
            // Add New Book
            else if ( $action == 'add-new-book' ) {

                $req = [
                    'book_title' => [
                        'required' => true,
                        'label' => 'Book Title'
                    ],
                    'book_author' => [
                        'required' => true,
                        'label' => 'Author Name'
                    ],
                    'published_year' => [
                        'required' => true,
                        'label' => 'Published Year'
                    ],
                    'book_genre' => [
                        'required' => true,
                        'label' => 'Book Genre'
                    ]
                ];

                $validate = new Validate();
                $validation = $validate->check($this->formData, $req);

                if ( $validation->passed() == false ) {
                    $this->formResponse['error_fields'] = $validation->errors();
                    throw new Exception('Required fields are missing', 400);
                }

                $book = new Book();
                $book->setPayload('book_title', $this->formData['book_title']);
                $book->setPayload('book_genre', $this->formData['book_genre']);
                $book->setPayload('book_author', $this->formData['book_author']);
                $book->setPayload('published_year', $this->formData['published_year']);

                if ( ! $book->store() ) {
                    throw new Exception('Unable to add new book', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'New book added successfully';

            }
            // Edit device
            else if ( $action == 'show-book' ) {
                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.books._update', [
                    'book' => (new Book($param))->selectBookById( $param )
                ]);
            }
            // Update book information
            else if ( $action == 'update-book' ) {

                if ( $param == null ) {
                    throw new Exception('Unidentified book', 404);
                }

                $bookId = $param;

                $req = [
                    'book_title' => [
                        'required' => true,
                        'label' => 'Book Title'
                    ],
                    'book_author' => [
                        'required' => true,
                        'label' => 'Author Name'
                    ],
                    'published_year' => [
                        'required' => true,
                        'label' => 'Published Year'
                    ],
                    'book_genre' => [
                        'required' => true,
                        'label' => 'Book Genre'
                    ]
                ];

                $validate = new Validate();
                $validation = $validate->check($this->formData, $req);

                if ( $validation->passed() == false ) {
                    $this->formResponse['error_fields'] = $validation->errors();
                    throw new Exception('Required fields are missing', 400);
                }

                $book = new Book( $bookId );
                $book->setPayload('book_title', $this->formData['book_title']);
                $book->setPayload('book_genre', $this->formData['book_genre']);
                $book->setPayload('book_author', $this->formData['book_author']);
                $book->setPayload('published_year', $this->formData['published_year']);

                if ( ! $book->update() ) {
                    throw new Exception('Unable to update book', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Book updated successfully';

            }
            // remove book
            else if ( $action == 'remove-book' ) {

                $bookId = $param;

                $book = new Book( $bookId );

                if ( ! $book->removeBook() ) {
                    throw new Exception('Unable to remove book', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Book removed successfully';

            }
            else {
                throw new Exception('Invalid action', 404);
            }

        }
        catch ( Exception $ex ) {
            $this->setError( $ex->getMessage(), $ex->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }
    }
    
    private function validateFields($requiredFields) 
    {
        foreach ( $requiredFields as $f ) {
            if ( array_key_exists($f, $this->formData) && empty($this->formData[$f]) ) {
                $f = ucwords(str_replace('_', ' ', $f));
                throw new Exception($f . ' is required');
            }
        }
    }

}