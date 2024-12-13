<?php
// Configuración del endpoint de la API
$apiUrl = "http://127.0.0.1:8000/chat"; // Cambia esta URL si tu backend está alojado en otro lugar o puerto

// Verificar si hay un mensaje POST desde el frontend
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_input"])) {
    $userInput = $_POST["user_input"];
    
    // Crear la solicitud POST a la API
    $data = ["user_input" => $userInput];
    $options = [
        "http" => [
            "header"  => "Content-Type: application/json\r\n",
            "method"  => "POST",
            "content" => json_encode($data),
        ]
    ];
    $context  = stream_context_create($options);

    // Hacer la solicitud
    $response = file_get_contents($apiUrl, false, $context);

    // Añade esta línea justo después de `file_get_contents` en chatbot_api.php
    if ($response === FALSE) {
    echo json_encode(["error" => "Error al conectar con la API", "debug" => error_get_last()]);
    exit;
    }
    
    // Agrega este bloque justo antes de `header('Content-Type: application/json');`
    if (empty($response)) {
    echo json_encode(["error" => "La API no devolvió datos"]);
    exit;
    }

    // Devolver la respuesta de la API al frontend
    header('Content-Type: application/json');
    echo $response;
    exit;
}

// Si no se recibe un mensaje válido
echo json_encode(["error" => "Solicitud no válida"]);
exit;
?>
