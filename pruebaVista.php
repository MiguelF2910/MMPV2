<?php
// Ruta al intérprete de Python en el entorno virtual
$pythonPath = 'E:\\xampp\\htdocs\\MMPV2\\.venv\\Scripts\\python.exe';

// Ruta al script Python
$scriptPath = 'E:\\xampp\\htdocs\\MMPV2\\pruebaTerminal.py';

// Ejecutar el script Python con el intérprete del entorno virtual
$command = escapeshellcmd("$pythonPath $scriptPath");
$response = shell_exec($command);

// Mostrar la respuesta
echo "<h1>Respuesta del Chatbot:</h1>";
echo "<p>" . htmlspecialchars($response) . "</p>";
?>
