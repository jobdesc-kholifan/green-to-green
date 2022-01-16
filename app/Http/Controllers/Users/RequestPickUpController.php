<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

class RequestPickUpController extends Controller
{

    protected $viewPath = "pages.pickup";
    protected $title = "Pemesanan Pickup";

    public function __construct()
    {

    }

    public function index()
    {
        try {
            return $this->view('pickup');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
