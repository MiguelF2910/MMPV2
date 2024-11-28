<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Interface</title>
    <link rel="stylesheet" href="./styles/styles_chatbot.css">
    <link rel="shortcut icon" href="./imagenes/mico.ico">
</head>
<body>
    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <div class="user-info">
                <div class="user-avatar"></div>
                <div class="user-details">
                    <h3>Asistente de Micromasterpieces</h3>
                    <p>Asistente virtual</p>
                </div>
            </div>
            <div class="chat-actions">
                <!--button class="action-btn">📹</button>
                <button class="action-btn">📞</button!-->
                <button class="action-btn">⚙️</button>
            </div>
        </div>

        <!-- Chat messages -->
        <div class="chat-messages">
            <div class="message received">
                <p>Hola</p>
            </div>
            <div class="message received">
                <p>¿Hola como estás? ¿Deseas un presupuesto o explorar nuestro catálogo?</p>
            </div>
            <div class="message sent">
                <p>Sí</p>
            </div>
            <div class="message sent">
                <p>Tengo un presupuesto de 12,000, me gustaria: ....</p>
            </div>
            <div class="file-message">
                <p>PRESUPUESTO.pdf</p>
                <button class="download-btn">Descargar</button>
            </div>
        </div>

        <!-- Input field -->
        <div class="chat-input">
            <input type="text" placeholder="Type a message">
            <button class="send-btn">➤</button>
        </div>
    </div>
</body>
</html>
