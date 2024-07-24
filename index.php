<?php
session_start();
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $_SESSION['name'] = $name;
}

if (isset($_POST['reset'])) {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WebSocket Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        form, .chat-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .chat-container {
            width: 100%;
        }
        .input-container {
            display: flex;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="submit"], input[type="button"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"] {
            flex-grow: 1;
            margin-right: 10px;
        }
        input[type="submit"], input[type="button"] {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #0056b3;
        }
        #msg_box {
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            overflow-y: auto;
            margin-bottom: 10px;
            background-color: #fafafa;
        }
        .message {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }
        .message .bubble {
            padding: 10px;
            border-radius: 10px;
            max-width: 70%;
            word-wrap: break-word;
        }
        .message.you .bubble {
            background-color: #007BFF;
            color: white;
            align-self: flex-start;
        }
        .message.me .bubble {
            background-color: #eee;
            align-self: flex-end;
        }
        .status {
            font-style: italic;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <?php 
    if (!isset($_SESSION['name'])) {
    ?>
    <form method="post">
        <input type="text" name="name" required placeholder="Enter your name">
        <input type="submit" name="submit" value="Submit">
    </form>
    <?php } else { ?>
    <div class="chat-container">
        <div id="msg_box"></div>
        <div class="input-container">
            <input type="text" id="msg" placeholder="Enter your message">
            <input type="button" id="send_btn" value="Send">
        </div>
        <div class="input-container">
            <input type="button" id="start_btn" value="Start Connection">
            <input type="button" id="terminate_btn" value="Terminate Connection">
            <form method="post" style="display: inline;">
                <input type="submit" name="reset" value="Reset">
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script>
    var conn;

    function showMessage(message) {
        var html = "";
        if (message.type === "status") {
            html = "<div class='status'>" + message.name + " " + message.status + " the connection.</div>";
        } else if (message.type === "message") {
            var messageClass = message.name === "<?php echo $_SESSION['name']; ?>" ? "me" : "you";
            html = "<div class='message " + messageClass + "'><div class='bubble'><b>" + message.name + "</b>: " + message.msg + "</div></div>";
        }
        jQuery('#msg_box').append(html);
        jQuery('#msg_box').scrollTop(jQuery('#msg_box')[0].scrollHeight);
    }

    function sendMessage() {
        if (conn && conn.readyState === WebSocket.OPEN) {
            var msg = jQuery('#msg').val();
            var name = "<?php echo $_SESSION['name']; ?>";
            var content = {
                type: "message",
                msg: msg,
                name: name
            };
            conn.send(JSON.stringify(content));
            
            showMessage(content);
            jQuery('#msg').val('');
        } else {
            alert("Connection is not open.");
        }
    }

    jQuery('#start_btn').click(function() {
        jQuery('#msg_box').empty(); // Clear previous messages
        conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            console.log("Connection established!");
            var name = "<?php echo $_SESSION['name']; ?>";
            var content = {
                type: "status",
                status: "connected",
                name: name
            };
            conn.send(JSON.stringify(content));
        };
        
        conn.onmessage = function(e) {
            var getData = jQuery.parseJSON(e.data);
            showMessage(getData);
        };
        
        conn.onclose = function(e) {
            console.log("Connection closed!");
        };
    });

    jQuery('#terminate_btn').click(function() {
        if (conn) {
            var name = "<?php echo $_SESSION['name']; ?>";
            var content = {
                type: "status",
                status: "terminated",
                name: name
            };
            conn.send(JSON.stringify(content));
            conn.close();
        }
    });

    jQuery('#send_btn').click(function() {
        sendMessage();
    });

    // Send message on Enter key press
    jQuery('#msg').keypress(function(e) {
        if (e.which == 13) {
            sendMessage();
            return false;
        }
    });
    </script>
    <?php } ?>
</div>

</body>
</html>
