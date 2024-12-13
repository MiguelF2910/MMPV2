<?php
// Ruta al intérprete de Python en el entorno virtual
<<<<<<< HEAD
$pythonPath = 'E:\\xampp\\htdocs\\MMPV2\\.venv\\Scripts\\python.exe';

// Ruta al script Python
$scriptPath = 'E:\\xampp\\htdocs\\MMPV2\\pruebaTerminal.py';
=======
$pythonPath = 'C:\\xampp\\htdocs\\MMPV2\\.venv\\Scripts\\python.exe';

// Ruta al script Python
$scriptPath = 'C:\\xampp\\htdocs\\MMPV2\\pruebaTerminal.py';
>>>>>>> c23a622b322f489e39eb59cb79952b13446a661d

// Ejecutar el script Python con el intérprete del entorno virtual
$command = escapeshellcmd("$pythonPath $scriptPath");
$response = shell_exec($command);

// Mostrar la respuesta
echo "<h1>Respuesta del Chatbot:</h1>";
echo "<p>" . htmlspecialchars($response) . "</p>";
?>
