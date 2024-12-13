document.addEventListener("DOMContentLoaded", () => {
    const chatMessages = document.getElementById("chat-messages");
    const messageInput = document.getElementById("message-input");
    const sendButton = document.getElementById("send-button");
  
    // Función para agregar un mensaje al panel de chat
    const addMessage = (text, isUser = true) => {
      const messageDiv = document.createElement("div");
      messageDiv.classList.add("message", isUser ? "user-message" : "bot-message");
      messageDiv.textContent = text;
      chatMessages.appendChild(messageDiv);
      chatMessages.scrollTop = chatMessages.scrollHeight; // Desplazarse hacia abajo
    };
  
    // Función para enviar el mensaje al servidor
    const sendMessage = async () => {
      const userInput = messageInput.value.trim();
      if (!userInput) return; // No enviar mensajes vacíos
  
      // Agregar el mensaje del usuario al chat
      addMessage(userInput, true);
      messageInput.value = "";
  
      try {
        // Hacer la solicitud POST a chatbot_api.php
        const response = await fetch("controller/api_handler.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams({ user_input: userInput }),
        });
  
        const data = await response.json();
        if (data.error) {
          addMessage("Hubo un error: " + data.error, false);
        } else {
          // Mostrar el mensaje del bot en el chat
          addMessage(data.message_from_ai, false);
          
          // Si hay componentes sugeridos, mostrarlos
          if (data.components) {
            Object.entries(data.components).forEach(([component, details]) => {
              addMessage(`Componente: ${component}`, false);
              Object.entries(details.prices).forEach(([site, price]) => {
                addMessage(`Precio en ${site}: $${price}`, false);
              });
            });
  
            // Mostrar el precio total
            if (data.total_price) {
              addMessage(`Precio total estimado: $${data.total_price}`, false);
            }
          }
        }
      } catch (error) {
        console.error("Error:", error);
        addMessage("Error al procesar la solicitud. Inténtalo de nuevo.", false);
      }
    };
  
    // Manejar el clic del botón de enviar
    sendButton.addEventListener("click", sendMessage);
  
    // Manejar la tecla Enter
    messageInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter") sendMessage();
    });
  });
  