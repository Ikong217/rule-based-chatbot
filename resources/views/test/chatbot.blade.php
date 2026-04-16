<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>

<body>

    <div id="chat" style="border:1px solid #ccc; height:300px; overflow-y:auto; padding:10px; margin-bottom:10px;">
    </div>
    <input id="msg" placeholder="Type a message..." style="width:80%; padding:8px;" />
    <button onclick="send()" style="padding:8px 12px;">Send</button>

    <script>
        async function send() {
            const input = document.getElementById('msg');
            const chat = document.getElementById('chat');
            const message = input.value.trim();
            if (!message) return;

            // Show user message
            chat.innerHTML += `<div><b>You:</b> ${message}</div>`;
            input.value = '';

            // Create bot bubble
            const botDiv = document.createElement('div');
            botDiv.innerHTML = '<b>Bot:</b> ';
            chat.appendChild(botDiv);

            // Call the simple JSON endpoint
            const res = await fetch('/chatbot/respond', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message
                })
            });

            const data = await res.json();
            const words = data.reply.split(' ');

            // Type out words one by one (fake stream)
            let i = 0;
            const interval = setInterval(() => {
                if (i < words.length) {
                    botDiv.innerHTML += words[i] + ' ';
                    i++;
                    chat.scrollTop = chat.scrollHeight;
                } else {
                    clearInterval(interval);
                }
            }, 80); // 80ms per word
        }

        // Send on Enter key
        document.getElementById('msg').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') send();
        });
    </script>

</body>

</html>
