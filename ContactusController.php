<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\contactus;
use Illuminate\Http\Request;

class ContactusController  extends Controller
{
    public function index()
    {
       
        $contactus = contactus::all();

       
        return view('dashboard.contactus.index',compact('contactus'));
    }
}