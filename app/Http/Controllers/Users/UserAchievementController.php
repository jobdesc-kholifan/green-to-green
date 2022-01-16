<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserAchievementController extends Controller
{

    protected $viewPath = "pages.achievements";
    protected $title = "Achievement Pengguna";

    public function __construct()
    {

    }

    public function index(Request $req)
    {
        try {

            return $this->view('achievement');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
