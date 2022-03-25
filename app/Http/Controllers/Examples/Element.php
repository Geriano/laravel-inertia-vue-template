<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Element extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function general()
    {
        return Inertia::render('Examples/Element/General');
    }
}
