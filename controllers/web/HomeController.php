<?php
/**
 * Home Controller
 * @author Ahmed Shan (ahmed.shan)
 * 
 */

use Heliumframework\Controller;
use Heliumframework\Auth;
use Heliumframework\Hash;
use Heliumframework\Model\Audit;
use Heliumframework\Requests;

class HomeController extends Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirectTo('/cp');
    }
    

}