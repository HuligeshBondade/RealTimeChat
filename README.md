# WebSocket Chat Application

This project is a simple WebSocket-based chat application built with PHP, HTML, CSS, and JavaScript. Users can enter their name to join the chat, send messages, and see messages from other users in real-time.

## Features

- **User Session Management**: Users can join the chat by entering their name.
- **WebSocket Connection**: Real-time communication using WebSockets.
- **Chat Interface**: Send and receive messages dynamically.
- **Connection Status**: Notify when users connect or disconnect.

## Screenshots

![chat](https://github.com/user-attachments/assets/a6918555-d29f-4df6-a730-961f5bb9fe6a)
![websocket](https://github.com/user-attachments/assets/cec9be78-f4b3-4372-ab68-9fb0f406e10b)

### Prerequisites

- PHP
- A WebSocket server (e.g., Ratchet, Node.js WebSocket)

### Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/your-username/websocket-chat-app.git
    cd websocket-chat-app
    ```

2. Start the PHP server:
    ```sh
    php -S localhost:8000
    ```

3. Start your WebSocket server (make sure it's running on `ws://localhost:8080`).

### Directory Structure

- **index.php**: Main PHP file handling sessions and the chat interface.
- **public/css/styles.css**: CSS styles for the chat interface.
- **public/js/script.js**: JavaScript for handling WebSocket communication and UI updates.

### Usage

1. Open your web browser and navigate to `http://localhost:8000`.
2. Enter your name and click "Submit" to join the chat.
3. Use the chat interface to send and receive messages in real-time.
4. Click "Start Connection" to connect to the WebSocket server.
5. Click "Terminate Connection" to disconnect from the WebSocket server.
6. Click "Reset" to clear the session and start over.

