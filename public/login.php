<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION["username"])) {
    // El usuario ya ha iniciado sesión, redirigir a la página de inicio o cualquier otra página protegida.
    header("Location: index.php");
    exit();
}

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores enviados del formulario
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // Incluir el archivo de conexión
    include('conexion.php');

    // Consultar la base de datos para obtener la información del usuario
    $sql = "SELECT contra, rol FROM usuarios WHERE username = '$usuario'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        // El usuario existe, obtener la contraseña hasheada de la base de datos
        $row = $result->fetch_assoc();
        $hashed_password = $row["contra"];

        // Verificar si la contraseña ingresada coincide con la contraseña hasheada
        if (password_verify($password, $hashed_password)) {
            // Las credenciales son válidas, el usuario puede iniciar sesión
            echo "Inicio de sesión exitoso. Bienvenido, $usuario!";
            
            // Guardar el nombre de usuario en la sesión para mantener la sesión activa
            $_SESSION["username"] = $usuario;
            $_SESSION["rol"] = $row["rol"];

            // Redirigir a la página de inicio o cualquier otra página protegida
            header("Location: index.php");
            exit();
        } else {
            // Las credenciales son inválidas
            echo "Usuario o contraseña incorrectos.";
        }
    } else {
        // El usuario no existe en la base de datos
        echo "Usuario o contraseña incorrectos.";
    }

    // Cerrar la conexión a la base de datos
    $mysqli->close();
}
?>



<!DOCTYPE html>
<html>
<head>
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./styles/estilo_login.css">
</head>
<body>
  <div class="container">
    <div class="login-form">
      <div class="logo">
        <img src="imagenes/logo3.png" alt="Logo del negocio">
      </div>
      <form action="" method="POST">
        <div class="form-group">
          <label for="usuario">Usuario</label>
          <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Nombre de Usuario">
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
        </div>
        <button type="submit" class="btn btn-primary" method="POST">Iniciar Sesión</button>
      </form>
    </div>
  </div>
</body>
</html>
