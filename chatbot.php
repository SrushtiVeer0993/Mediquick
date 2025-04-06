<?php
require_once 'includes/config.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Health Assistant - MediQuick</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .chatbot-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), #4a90e2);
            border-radius: 30px;
            position: relative;
            overflow: hidden;
            margin-top: 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .chatbot-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('../assets/images/pattern.png') repeat;
            opacity: 0.1;
        }

        .chatbot-header {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .chatbot-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }

        .chatbot-header p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
            color: white;
        }

        .chat-window {
            position: relative;
            z-index: 2;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: 500px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .message {
            max-width: 80%;
            padding: 0.8rem 1.2rem;
            border-radius: 15px;
            margin-bottom: 0.5rem;
        }

        .user-message {
            background: var(--primary-color);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }

        .bot-message {
            background: var(--light-gray);
            color: var(--text-color);
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }

        .chat-input {
            padding: 1rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 1rem;
        }

        .chat-input input {
            flex: 1;
            padding: 0.8rem;
            border: 1px solid var(--border-color);
            border-radius: 25px;
            outline: none;
        }

        .chat-input button {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 0.8rem 1.5rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .chat-input button:hover {
            background: var(--primary-dark);
        }

        .typing-indicator {
            display: none;
            align-self: flex-start;
            background: var(--light-gray);
            padding: 0.8rem 1.2rem;
            border-radius: 15px;
            margin-bottom: 0.5rem;
        }

        .typing-indicator span {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: var(--text-color);
            border-radius: 50%;
            margin-right: 3px;
            animation: typing 1s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .sources {
            font-size: 0.8rem;
            color: var(--secondary-color);
            margin-top: 0.5rem;
        }

        @media (max-width: 1200px) {
            .chatbot-container {
                max-width: 90%;
                margin-left: auto;
                margin-right: auto;
            }
        }

        @media (max-width: 768px) {
            .chatbot-container {
                padding: 2rem 1rem;
                margin: 2rem 1rem;
            }

            .chatbot-header h1 {
                font-size: 2rem;
            }

            .chatbot-header p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Chatbot Section -->
    <div class="chatbot-container">
        <div class="chatbot-header">
            <h1>Medical Assistant</h1>
            <p>Get instant medical advice and information</p>
        </div>

        <div class="chat-window">
            <div class="chat-messages" id="chatMessages">
                <div class="message bot-message">
                    Hello! I'm your AI Health Assistant. How can I help you today?
                </div>
            </div>
            <div class="typing-indicator" id="typingIndicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="chat-input">
                <input type="text" id="userInput" placeholder="Type your health-related question..." autocomplete="off">
                <button onclick="sendMessage()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="assets/js/main.js"></script>
    <script>
        const chatMessages = document.getElementById('chatMessages');
        const userInput = document.getElementById('userInput');
        const typingIndicator = document.getElementById('typingIndicator');
        let conversationId = Date.now().toString();

        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
            
            // Handle line breaks in the message
            const formattedMessage = message.replace(/\n/g, '<br>');
            messageDiv.innerHTML = formattedMessage;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function showTypingIndicator() {
            typingIndicator.style.display = 'block';
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function hideTypingIndicator() {
            typingIndicator.style.display = 'none';
        }

        function sendMessage() {
            const message = userInput.value.trim();
            if (!message) return;

            // Add user message to chat
            addMessage(message, true);
            userInput.value = '';

            // Show typing indicator
            showTypingIndicator();

            // Send message to API
            fetch('api/chatbot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    uid: conversationId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                hideTypingIndicator();
                if (data.success) {
                    addMessage(data.response);
                    if (data.sources && data.sources.length > 0) {
                        const sourcesDiv = document.createElement('div');
                        sourcesDiv.className = 'sources';
                        sourcesDiv.textContent = 'Sources: ' + data.sources.join(', ');
                        chatMessages.appendChild(sourcesDiv);
                    }
                } else {
                    addMessage('I apologize, but I\'m having trouble processing your request. Please try rephrasing your question or try again later.');
                }
            })
            .catch(error => {
                hideTypingIndicator();
                console.error('Error:', error);
                addMessage('I apologize, but I\'m having trouble processing your request. Please try rephrasing your question or try again later.');
            });
        }

        // Send message on Enter key
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Focus input on page load
        window.onload = function() {
            userInput.focus();
        };
    </script>
</body>
</html>