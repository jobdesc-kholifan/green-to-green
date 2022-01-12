<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    protected $title = "Login";
    protected $viewPath = "auth";

    protected $breadcrumbs = [];

    public function login()
    {
        try {
            return $this->view('login');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
