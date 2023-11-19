<?php
/**
 * Profile Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Session;
use Heliumframework\Model\User;

class ProfileController extends Controller {

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.profile.main');
    }

    public function ajaxHandler( $action = '', $param = '' )
    {
        try {

            // Tour
            if ( $action == 'tour-done' ) {

                $userId = loggedUser()->user_id;
                
                $user = new User($userId);
                $user->setPayload('take_tour', '0');
                if ( ! $user->update() ) {
                    throw new Exception('Unable to update tour', 400);
                }

                // Update session information
                $session = Session::get('user');
                $session['tour'] = 0;
                Session::put('user', $session);

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Thank you for taking the tour';

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

}