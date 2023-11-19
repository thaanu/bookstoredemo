<?php
/**
 * Session Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Auth;
use Heliumframework\Controller;
use Heliumframework\Session;
use Heliumframework\Validate;
use Heliumframework\Hash;
use Heliumframework\Model\Audit;
use Heliumframework\Model\User;
use Heliumframework\Model\Group;

class SessionController extends Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        // Check if user already logged in, then redirect to dashboard
        if ( Auth::isLoggedIn() ) {
            redirectTo(cpanelPermalink('dashboard'));
        }

        $this->view('admin-panel.login.main');
    }

    public function processLogin()
    {
        try {

            $formReq = [
                'email' => [
                    'required' => true,
                    'label' => 'Email'
                ],
                'password' => [
                    'required' => true,
                    'label' => 'Password'
                ]
            ];

            $validate = new Validate();
            $validation = $validate->check($this->formData, $formReq);

            if ( $validation->passed() == false ) {
                $this->formResponse['error_fields'] = $validation->errors();
                throw new Exception('Please check the required fields', 400);
            }

            $email = $this->formData['email'];
            $password = $this->formData['password'];

            $user = (new User())->selectUserByEmail( $email );
            
            if ( $user['count'] == 0 ) {
                throw new Exception('User not found', 404);
            }
            
            $user = (object) $user['data'];
            $salt = $user->salt;

            // Check if password is correct
            if ( Hash::make( $password, $salt ) != $user->password ) {
                Audit::setAudit($user->email, 'User', 'Failure', 'Session', "Invalid password");
                throw new Exception('Invalid password', 400);
            }

            $roles = (new Group())->selectRoles( $user->user_group );

            $userSession = [
                'user_id'       => $user->user_id,
                'display_name'  => $user->full_name,
                'email'         => $user->email,
                'user_group'    => $user->user_group,
                'roles'         => $roles,
                'tour'          => $user->take_tour
            ];

            Auth::setUserSession($userSession);

            Audit::setAudit($user->email, 'User', 'Success', 'Session', "Logged in");

            $this->formResponse = [
                'status' => true,
                'textMessage' => 'Welcome ' . $user->full_name,
                'dataNs' => cpanelPermalink('dashboard')
            ];

        }
        catch ( Exception $ex ) {
            $this->formResponse['error'] = $ex->getMessage();
        }
        finally {
            $this->sendJsonResponse();
        }
    }

    public function logout()
    {
        $username   = Session::get('user')['username'];

        Session::delete('user');

        // Set audit
        Audit::setAudit($username, 'User', 'Success', 'Session', "Logged out");
        
        // Redirect to home
        RedirectTo(cpanelPermalink(''));
    }

    private function _curl( $url, $payload = [] )
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $header = [
            'Content-type:application/json',
            'Auth-Key:' . (string) _env('AD_APP_KEY')
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        return json_decode($server_output);
    }

}