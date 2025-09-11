<?php
Namespace App\Http\Controllers;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index() {
        return view('/booking');
    }

    public function checkAvail(Request $request){}
}