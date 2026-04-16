<?php

use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',function(){
    return view('chatbot');
});

Route::get('design',function(){
    return view('test.design');
});

Route::post('chat/send',[ChatController::class, 'sendChat'])->name('chat.send');

Route::get('/chatbot',fn()=>view('test.chatbot'))->name('chatbot.render');
Route::post('/chatbot/respond', [ChatBotController::class, 'respond']);
Route::post('/chatbot/stream',  [ChatBotController::class, 'stream']);
Route::get('/chatbot-test', function () {
    $bot = new \App\Chatbot\ChatBot();

    $tests = ['price','how long does delivery take','secret','get current responder'];
    foreach ($tests as $msg) {
        echo "<b>You:</b> $msg <br>";
        echo "<b>Bot:</b> " . $bot->getResponse($msg) . "<br><br>";
    }
});
