<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function show()
    {
        return response('alive', 200);
    }
}
