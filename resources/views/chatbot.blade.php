<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/root.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chatbot.css') }}">

    <title>Chatbot Testing</title>
</head>

<body class="theme-prim">
    <div class="content">
        <div class="content-wrapper">

            <button onclick="toggleTheme()" class="toggle-btn">
                Toggle Theme
            </button>

            <form class="form-message theme-sec" method="POST" action="{{ route('chat.send') }}">
                @csrf
                <h1 class="theme-text">Type a message</h1>

                <div class="input-container">
                    <label for="message" class="theme-text">Message</label>
                    <input type="text" class="theme-inp" id="message" name="message">
                </div>

                <button class="theme-btn">Send</button>
            </form>

        </div>
    </div>

    <script src="{{ asset('assets/js/themes.js') }}"></script>
</body>
</html>
