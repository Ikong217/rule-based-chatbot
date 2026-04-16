<?php
namespace App\Http\Controllers;

use App\Chatbot\Core\ChatBot;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function sendChat(Request $request)
    {
        $bot = new ChatBot();
        $bot->getResponse($request->message);
        dd($bot->getMessages());
    }
}
