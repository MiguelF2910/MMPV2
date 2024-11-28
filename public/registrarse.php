<?php
// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores enviados del formulario
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $rol = 2;
    // Valores de los campos de interés
     // Valor por defecto
    if (isset($_POST["interes"])) {
        // Si el checkbox está marcado, asignar el valor 1 a $interes
        $interes = 'Laptops';
    } else if(isset($_POST["interes2"])) {
        $interes='PC Gamer';
    }  else if(isset($_POST["interes3"])) {
      $interes ='Telefonia Celular';
    } else if(isset($_POST["interes4"])) {
      $interes = 'Electronica';
    }

    // Incluir el archivo de conexión
    require('conexion.php');

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta para insertar los datos en la tabla usuarios
    $sql = "INSERT INTO usuarios (username, contra, nombre_completo, email, intereses, rol) VALUES ('$usuario', '$hashed_password', '$nombre', '$correo','$interes','$rol')";

    // Ejecutar la consulta
    if ($mysqli->query($sql) === TRUE) {
        echo "Registro exitoso. Los datos han sido insertados en la base de datos.";
        header("Location: index.php");
        exit();
    } else {
        echo "Error al registrar los datos: " . $mysqli->error;
    }

    // Cerrar la conexión a la base de datos
    //$mysqli->close();
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Registro</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./styles/estilo_registro.css">
</head>
<body>
  <div class="container">
    <div class="registration-form">
      <div class="logo">
        <img src="imagenes/logo3.png" alt="Logo del negocio">
      </div>
      <form action="#" method="POST">
        <div class="form-group">
          <label for="name">Nombre completo</label>
          <input type="text" class="form-control" name="nombre" placeholder="Nombre completo">
        </div>
        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input type="email" class="form-control" name="correo" placeholder="Correo electrónico">
        </div>
        <div class="form-group">
          <label for="username">Nombre de Usuario</label>
          <input type="text" class="form-control" name="usuario" placeholder="Nombre de Usuario">
        </div>
        <div class="form-group">
          <label for="contra">Contraseña</label>
          <input type="password" class="form-control" name="password" placeholder="Contraseña">
        </div>
        <div class="form-group">
          <label for="confirmar-password">Confirmar Contraseña</label>
          <input type="password" class="form-control" id="confirmar-password" placeholder="Confirmar Contraseña">
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="interes" name="interes" value="Laptops">
          <label class="form-check-label" for="interes">Laptops</label>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="interes2" name="interes2" value="Equipo Gamer">
          <label class="form-check-label" for="interes2">Equipo Gamer</label>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="interes3" name="interes3" value="Telefonía Celular">
          <label class="form-check-label" for="interes3">Telefonía Celular</label>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="interes4" name="interes4" value="Electrónica">
          <label class="form-check-label" for="interes4">Electrónica</label>
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
      </form>
    </div>
  </div>
</body>
</html>
