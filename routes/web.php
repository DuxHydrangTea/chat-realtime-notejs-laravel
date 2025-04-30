<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Models\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;


Route::group(
    [
        'middleware' => ['auth'],
        'controller' => HomeController::class,
    ],
    function () {
        Route::get('/', 'index')->name('home');
        Route::post('send-messages', 'sendMessages')->name('send_messages');
    }
);


Route::get('login', [AuthController::class, 'login'] )->name('login');
Route::post('login', [AuthController::class, 'handleLogin'] )->name('handle_login');



// Route::post('send-messages', function (HttpRequest $request) {
//     $userId = Auth::id();
//     $message = $request->input('message');
//     $receiverId = $request->input('receiver_id');
//     $sended = Message::create([
//         'message' => $message,
//         'sender' => $userId,
//         'receiver' => $receiverId,
//     ]);
//     if($sended){
//         return response()->json(['success' => true], Response::HTTP_OK);
//     }
//     else{
//         return response()->json(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
// })->name('send_messages');