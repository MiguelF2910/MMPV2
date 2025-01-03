document.addEventListener("DOMContentLoaded", () => {
  const chatMessages = document.getElementById("chat-messages");
  const messageInput = document.getElementById("chat-input");
  const sendButton = document.getElementById("send-btn");

  // Función para agregar un mensaje al panel de chat
  const addMessage = (text, isUser = true) => {
    const messageDiv = document.createElement("div");
    messageDiv.classList.add("message", isUser ? "sent" : "received");
  
    if (!isUser) {
      // Agregar el avatar del bot solo en los mensajes recibidos
      const botAvatar = document.createElement("div");
      botAvatar.classList.add("bot-avatar");
      messageDiv.appendChild(botAvatar);
    }
  
    const messageText = document.createElement("p");
    messageText.textContent = text;
    messageDiv.appendChild(messageText);
  
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight; // Desplazarse hacia abajo
  };
  

  // Función para agregar la animación de "pensando"
  const addThinkingBubble = () => {
    const thinkingDiv = document.createElement("div");
    thinkingDiv.classList.add("thinking");
    thinkingDiv.textContent = "Pensando...";
    chatMessages.appendChild(thinkingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight; // Desplazarse hacia abajo
    return thinkingDiv; // Devuelve el elemento para poder eliminarlo después
  };

  // Función para enviar el mensaje al servidor
  const sendMessage = async () => {
    const userInput =
      messageInput.value.trim() +
      " Pero ¿me podrías devolver todos los componentes dentro de corchetes y entre comillas simples?. Solo una serie de componentes, y si hay graficas integradas en el cpu, solo omitelas de la lista y no las menciones. Ejemplo: ['Componente1', 'Componente2']";
    if (messageInput.value === "") return; // No enviar mensajes vacíos
    const validKeywords = ["procesador", "ram", "placa base", "motherboard", "GPU", "cpu", "SSD", "fuente de poder", "gabinete","presupuesto","pc","computadora","ordenador","informatica"];
    const isInputValid = (input) => validKeywords.some((keyword) => input.toLowerCase().includes(keyword));
    
    if (!isInputValid(messageInput.value.trim())) {
        addMessage(messageInput.value.trim(), true);
        addMessage("Por favor, ingresa una consulta válida para poder entregarte un presupuesto.", false);
        return;
    } else {
      const invalidKeywords = ["-","-","{","SELECT","DELETE","JOIN"];
      const isOffTopic = (input) => invalidKeywords.some((keyword) => input.toLowerCase().includes(keyword));
      
      if (isOffTopic(messageInput.value.trim())) {
          addMessage(messageInput.value.trim(), true);
          addMessage("Lo siento, no admito valores negativos, inválidos. ¿Hay algo en lo que te pueda ayudar?", false);
          return;
      } else {
          // Agregar el mensaje del usuario al chat
    addMessage(messageInput.value.trim(), true);
    document.getElementById("chat-input").value = "";

    // Agregar la burbuja de "pensando"
    const thinkingBubble = addThinkingBubble();

    try {
      // Hacer la solicitud POST a chatbot_api.php
      const response = await fetch("controller/api_handler.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ user_input: userInput }),
      });

      const data = await response.json();

      // Eliminar la burbuja de "pensando"
      chatMessages.removeChild(thinkingBubble);

      if (data.error) {
        addMessage("Hubo un error: " + data.error, false);
      } else {
        // Mostrar el mensaje del bot en el chat
        addMessage(data.message_from_ai, false);

        // Si hay componentes sugeridos, mostrarlos
        if (data.components) {
          Object.entries(data.components).forEach(([component, details]) => {
            addMessage(`Componente: ${component}`, false);

            // Filtrar y mostrar solo precios válidos (no null)
            Object.entries(details.prices).forEach(([site, price]) => {
              if (price !== null) {
                addMessage(`Precio en ${site}: $${price}`, false);
              }
            });
          });

          // Mostrar el precio total si existe y es válido
          if (data.total_price) {
            addMessage(`Precio total estimado: $${data.total_price}`, false);
          }
        }
      }
    } catch (error) {
      console.error("Error:", error);

      // Eliminar la burbuja de "pensando"
      chatMessages.removeChild(thinkingBubble);

      addMessage("Error al procesar la solicitud. Inténtalo de nuevo.", false);
    }
    }
      }
    
  };

  // Manejar el clic del botón de enviar
  sendButton.addEventListener("click", sendMessage);

  // Manejar la tecla Enter
  messageInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") sendMessage();
  });
});
