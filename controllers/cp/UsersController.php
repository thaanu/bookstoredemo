<?php
/**
 * Users Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Model\User;
use Heliumframework\Model\Audit;
use Heliumframework\Model\Group;
use Heliumframework\Hash;
use Heliumframework\Logging;
use Heliumframework\Service\UserService;
use Heliumframework\Validate;
use Heliumframework\Session;

class UsersController extends Controller {

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.users.main');
    }

    public function ajaxHandler( $action = '' )
    {

        try {

            if ( $action == 'fetch-users' ) {

                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.users._data', [
                    'title' => 'Users',
                    'users' => (new User())->selectAll()
                ]);

            }

            else {
                throw new Exception('Invalid action', 500);
            }

        }
        catch ( Exception $e ) {
            $this->setError( $e->getMessage(), $e->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }

    }

    public function create()
    {
        try {
            $this->formResponse = [
                'status' => 200,
                'view' =>  $this->returnView('admin-panel.users._create', [
                    'groups' => $this->resortGroups()
                ])
            ];
        }
        catch( Exception $ex ) {
            $this->setError( $ex->getMessage(), $ex->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }
    }

    public function store()
    {
        try {

            $formReq = [
                'full_name' => [
                    'required' => true,
                    'label' => 'Full Name'
                ],
                'email' => [
                    'required' => true,
                    'label' => 'Email Address'
                ],
                'user_password' => [
                    'required' => true,
                    'label' => 'Password'
                ]
            ];

            $validate = new Validate();
            $validation = $validate->check( $this->formData, $formReq );

            if ( $validation->passed() == false ) {
                $this->formResponse['error_fields'] = $validation->errors();
                $this->throwExceptionWithAudit( 'Failure', 'Required fields are missing', 'Users', 'User' );
                throw new Exception('Please check the required fields');
            }

            $email = $this->formData['email'];

            $userService = new UserService();

            // Check if email already exists
            if ( $userService->emailExists($email) ) {
                throw new Exception($userService->getError());
            }

            $fullName = $this->formData['full_name'];

            $password = $this->formData['user_password'];
            $salt = Hash::salt(35);
            $encPassword = Hash::make($password, $salt);

            $createUser = new User();
            $createUser->setPayload('full_name', $this->formData['full_name']);
            $createUser->setPayload('email', $this->formData['email']);
            $createUser->setPayload('user_group', $this->formData['user_group']);
            $createUser->setPayload('password', $encPassword);
            $createUser->setPayload('salt', $salt);
            $createUser->setPayload('take_tour', '1'); // All the new users should take the tour

            if ( ! $createUser->store() ) {
                Audit::setAudit(NULL, 'User', 'Failure', 'Users', "Failed to create user $fullName due to " . $createUser->getError(), json_encode($this->formData));
                throw new Exception('Unable to create user');
            }

            // todo: schedule a welcome email

            Audit::setAudit(NULL, 'User', 'Success', 'Users', "User $fullName created", json_encode($this->formData));

            $this->formResponse = [
                'status' => 200,
                'textMessage' => 'User Created'
            ];

        }
        catch ( Exception $ex ) {
            $this->setError( $ex->getMessage(), $ex->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }
    }

    public function update( $userId = '' )
    {
        try {

            if ( empty($userId) ) {
                $this->throwExceptionWithAudit( 'Failure', 'Invalid user id', 'Users', 'User' );
            }

            $userModel = new User($userId);
            $userService = new UserService();

            // Check if user is deleted
            if ( $userService->userDeleted( $userId ) ) {
                $this->throwExceptionWithAudit( 'Failure', $userService->getError(), 'Users', 'User' );
            }

            $user = $userModel->selectUserById();

            $this->formResponse['status'] = 200;
            $this->formResponse['view'] = $this->returnView('admin-panel.users._update', [
                'groups' => $this->resortGroups(),
                'user' => (object) $user['data'],
                'count' => $user['count']
            ]);
        }
        catch( Exception $e ) {
            $this->setError( $e->getMessage(), $e->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }
    }

    public function put( $userId = '' )
    {
        try {

            if ( empty($userId) ) {
                $this->throwExceptionWithAudit( 'Failure', 'Invalid user id', 'Users', 'User' );
            }

            $userService = new UserService();

            // Check if user is deleted
            if ( $userService->userDeleted( $userId ) ) {
                $this->throwExceptionWithAudit( 'Failure', $userService->getError(), 'Users', 'User' );
            }

            $formReq = [
                'full_name' => [
                    'required' => true,
                    'label' => 'Full Name'
                ],
                'email' => [
                    'required' => true,
                    'label' => 'Email Address'
                ],
                'user_group' => [
                    'required' => true,
                    'label' => 'User Group'
                ]
            ];

            $validate = new Validate();
            $validation = $validate->check( $this->formData, $formReq );

            if ( $validation->passed() == false ) {
                $this->formResponse['error_fields'] = $validation->errors();
                $this->throwExceptionWithAudit( 'Failure', 'Required fields are missing', 'Users', 'User' );
                throw new Exception('Please check the required fields');
            }

            $email = $this->formData['email'];

            // Check if email already exists
            if ( $userService->emailExists($email, $userId) ) {
                throw new Exception($userService->getError());
            }

            $fullName = $this->formData['full_name'];
            $takeTour = ( isset($this->formData['take_tour']) ? 1 : 0 );
            
            $userUpdate = new User($userId);
            $userUpdate->setPayload('full_name', $this->formData['full_name']);
            $userUpdate->setPayload('email', $this->formData['email']);
            $userUpdate->setPayload('user_group', $this->formData['user_group']);
            $userUpdate->setPayload('take_tour', $takeTour);

            // Handle password
            if ( !empty($this->formData['user_password']) ) {
                $password = $this->formData['user_password'];
                $salt = Hash::salt(35);
                $encPassword = Hash::make($password, $salt);
                $userUpdate->setPayload('password', $encPassword);
                $userUpdate->setPayload('salt', $salt);
            }

            if ( ! $userUpdate->update() ) {
                Audit::setAudit(NULL, 'User', 'Failure', 'Users', "Failed to update user $fullName due to " . $userUpdate->getError(), json_encode($this->formData));
                throw new Exception('Unable to update user');
            }

            Audit::setAudit(NULL, 'User', 'Success', 'Users', "Updated user $fullName", json_encode($this->formData));

            $this->formResponse = [
                'status' => 200,
                'textMessage' => 'User Updated'
            ];

        }
        catch ( Exception $ex ) {
            $this->setError( $ex->getMessage(), $ex->getCode() );
        }
        finally {
            $this->sendJsonResponse();
        }
    }

    private function _generatePassword()
    {
        $random = rand(111111,999999);
        $salt = Hash::salt(35);
        $encPassword = Hash::make($random);
        return [$random, $encPassword, $salt];
    }

    private function resortGroups()
    {
        $groups = [];
        $records = (new Group())->selectAll();
        if ( $records['count'] > 0 ) {
            foreach ( $records['data'] as $row ) {
                $groups[$row['ug_id']] = $row['group_name'];
            }
        }
        return $groups;
    }

}