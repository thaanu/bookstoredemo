<?php
/**
 * Locations Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Model\Location;

class LocationsController extends Controller {

    public function __construct()
    {
        parent::__construct();
        Auth::userLogged();
    }

    public function index()
    {
        $this->view('admin-panel.locations.main');
    }

    public function ajaxHandler( $action = '', $param = '' )
    {
        try {

            // Fetch all locations
            if ( $action == 'fetch-locations' ) {
                $userId = loggedUser()->user_id;
                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.locations._data', [
                    'locations' => (new Location())->selectAllUserLocations($userId),
                ]);
            }
            // Show add location form
            else if ( $action == 'add-location-form' ) {
                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.locations._create');
            }
            // Process add location
            else if ( $action == 'add-location' ) {

                $requiredFields = ['location_name'];

                $this->validateFields( $requiredFields );
                
                $userId = loggedUser()->user_id;
                $locationName = $this->formData['location_name'];

                $location = new Location();
                $location->setPayload('location_name', $locationName);
                $location->setPayload('is_active', '1'); // By default set the location as active
                $location->setPayload('user_id', $userId);

                if ( ! $location->store() ) {
                    throw new Exception('Unable to add location', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Location added successfully';

            }
            // Show edit location form
            else if ( $action == 'show-location' ) {

                $userId = loggedUser()->user_id;
                $location = (new Location($param))->selectLocationById();

                if ( $location['count'] == 0 ) { throw new Exception('Location not found', 404); }
                if ( $location['data']['user_id'] != $userId ) { throw new Exception('This location does not belong to you', 401); }

                $this->formResponse['status'] = 200;
                $this->formResponse['view'] = $this->returnView('admin-panel.locations._update', [
                    'location' => $location['data']
                ]);
            }
            // process update location
            else if ( $action == 'update-location' ) {

                $locationId = $param;
                $userId = loggedUser()->user_id;
                $theLocation = (new Location($locationId))->selectLocationById();

                if ( $theLocation['count'] == 0 ) { throw new Exception('Location not found', 404); }
                if ( $theLocation['data']['user_id'] != $userId ) { throw new Exception('This location does not belong to you', 401); }

                $requiredFields = ['location_name'];
                $this->validateFields($requiredFields);
                
                $locationName = $this->formData['location_name'];
                $isActive = ( $this->formData['is_active'] == '1' ? 1 : 0 );

                $location = new Location( $locationId );
                $location->setPayload('location_name', $locationName);
                $location->setPayload('is_active', $isActive);

                if ( ! $location->update() ) {
                    throw new Exception('Unable to update location', 500);
                }

                $this->formResponse['status'] = 200;
                $this->formResponse['textMessage'] = 'Location updated successfully';

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
    
    private function validateFields( $requiredFields )
    {
        foreach ( $requiredFields as $f ) {
            if ( array_key_exists($f, $this->formData) && empty($this->formData[$f]) ) {
                $f = ucwords(str_replace('_', ' ', $f));
                throw new Exception($f . ' is required');
            }
        }
    }

}