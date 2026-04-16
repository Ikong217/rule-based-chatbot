<?php
namespace App\Http\Controllers;

use App\Chatbot\ChatBot;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    private ChatBot $bot;

    public function __construct()
    {
        $this->bot = new ChatBot();
    }

    // Normal JSON response
    public function respond(Request $request)
    {
        $message = $request->input('message', '');
        $reply = $this->bot->getResponse($message);

        return response()->json(['reply' => $reply]);
    }

    // ✅ Streaming / chunked response
    public function stream(Request $request)
    {
        $message = $request->input('message', '');
        $reply = $this->bot->getResponse($message);
        $words = explode(' ', $reply);

        return response()->stream(function () use ($words) {
            foreach ($words as $word) {
                echo "data: " . json_encode(['word' => $word . ' ']) . "\n\n";
                ob_flush();
                flush();
                usleep(80000); // 80ms per word
            }
            echo "data: [DONE]\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no', // important for nginx
        ]);
    }
}
