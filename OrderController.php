<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\order;
use Illuminate\Http\Request;

class OrderController  extends Controller
{
    public function index()
    {
       
        $order = order::all();

       
        return view('dashboard.order.index',compact('order'));
    }


    public function show($id)
    {
       
        $order = Order::findOrFail($id);

        
        return view('dashboard.order.show', compact('order'));
    }

}