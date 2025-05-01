<?php

namespace App\Http\Controllers;

use App\Models\MediaMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
class HomeController extends Controller
{
    //
    public function index(Request $request){
        $otherId = $request->input('other_id');
        $otherUser = User::find($otherId);
        $messages = [];
        if($otherId){
            $messages = Auth::user()->ourMessages( $otherId)->orderByDesc('id')->paginate(20)->sortBy('id');
        }
        // dd(Message::find(223)->with('mediaMessages'));
        $users = User::get()->except(['id' => Auth::id()]);
        return view('index', compact('users', 'messages', 'otherUser'));
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

        if($request->hasFile('file')){
            $files = $request->file('file');
            foreach($files as $file){
                $fileName = time().$file->getClientOriginalName();
                $file->move(public_path('uploads'), $fileName);
                $mediaType =  $file->getClientMimeType();

                if(str_contains($mediaType, 'image')){
                    $sended->media_type = Message::IMAGE;
                }

                if(str_contains($mediaType, 'video')){
                    $sended->media_type = Message::VIDEO;
                }

                /*
                Other cases...
                */
                MediaMessage::create([
                    'message_id' => $sended->id,
                    'media_path' => 'uploads/'.$fileName,
                ]);
            }
            
            $sended->save();
        }
        $sended->media_messages = $sended->mediaMessages;
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
