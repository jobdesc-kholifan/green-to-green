<?php

namespace App\Http\Controllers;

class AppController extends Controller
{

    protected $title = "Welcome to Green to Green";

    public function index()
    {
        try {
            return $this->view('home');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function admin()
    {
        try {
            return $this->view('admin');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
