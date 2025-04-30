<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Symfony\Component\HttpFoundation\Response;
class HomeController extends Controller
{
    //
    public function index(){
        return view('index');
    }

    public function login($id){
        Auth::loginUsingId($id);
        return redirect('/');
    }

    public function sendMessages(Request $request){
        $userId = Auth::id();
        $message = $request->input('message');
        $receiverId = $request->input('receiver_id');
        $sended = Message::create([
            'message' => $message,
            'sender' => $userId,
            'receiver' => $receiverId,
            'reply_id' => $request->input('reply_id') ?? 0,
        ]);
        $sended->replyMessage = $sended->reply->message ?? '';
        if($sended){
            return response()->json([
                'success' => true, 
                'message' => $sended,
        ], Response::HTTP_OK);
        }
        else{
            return response()->json(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
