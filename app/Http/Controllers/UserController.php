<?php

namespace App\Http\Controllers;

use App\Traits\BoardTraits;
use App\Traits\GameTraits;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use GameTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

}