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

    public function test()
    {
        $v =  $this->message_manager(1, 1, 'this is a test message !placer b15v badge');

        dd($v);
    }
}