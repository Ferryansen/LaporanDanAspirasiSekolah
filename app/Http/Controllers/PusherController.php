<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function index(){
        return view('report.studentStaff.chat');
    }

    public function broadcast(Request $request){
        broadcast(new PusherBroadcast($request->get('message')))->toOthers();
        return view('report.studentStaff.broadcast', ['message' => $request->get('message')]);
    }

    public function receive(Request $request){
        return view('report.studentStaff.receive', ['message' => $request->get('message')]);
    }
}
