<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
$iniciar_sesion_texto = "Iniciar sesión";
$iniciar_sesion_url = "login.php"; // Redireccionamiento a login

if (isset($_SESSION["username"])) {
    // El usuario ha iniciado sesión
    $iniciar_sesion_texto = "Cerrar sesión";
    $iniciar_sesion_url = "logout.php";
}
$username = $_SESSION["username"];
// Verificar el rol del usuario
if (isset($_SESSION["rol"]) && $_SESSION["rol"] == 1) {
    // El usuario es administrador, puede eliminar herramientas
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_herramienta"])) {
        // Recupera el ID de la herramienta a eliminar
        $id_herramienta = $_POST["id_herramienta"];
        $admin_password = $_POST["admin_password"];

        // Incluye la conexión a la base de datos
        require('conexion.php');

          // Prepara la consulta para obtener la contraseña encriptada del administrador
          $sql = "SELECT contra FROM usuarios WHERE username = '$username'"; 

          if ($result = $mysqli->query($sql)) {
              if ($result->num_rows === 1) {
                  $row = $result->fetch_assoc();
                  $admin_password_db_hash = $row["contra"];
  
                  // Verifica la contraseña del administrador
                  if (password_verify($admin_password, $admin_password_db_hash)) {
                      // La contraseña es correcta, procede con la eliminación
                      $sql_delete = "DELETE FROM herramientas WHERE id = ?";
  
                      if ($stmt = $mysqli->prepare($sql_delete)) {
                          $stmt->bind_param("i", $id_herramienta);
  
                          if ($stmt->execute()) {
                              echo "La herramienta ha sido eliminada con éxito.";
                          } else {
                              echo "Error al eliminar la herramienta: " . $stmt->error;
                          }
  
                          $stmt->close();
                      } else {
                          echo "Error al preparar la sentencia: " . $mysqli->error;
                      }
                  } else {
                      echo "Contraseña de administrador incorrecta. No se permitió la eliminación de la herramienta.";
                  }
              } else {
                  echo "No se encontró al administrador en la base de datos.";
              }
  
              $result->free();
          } else {
              echo "Error al consultar la base de datos: " . $mysqli->error;
          }
  
          $mysqli->close();
      }
  } else {
      // El usuario no tiene permisos para eliminar herramientas
      echo "No tiene permisos para eliminar herramientas.";
  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo_delherr.css">
    <link rel="stylesheet" href="stylesindex.css">
    <title>Eliminar Herramienta</title>
</head>
<header>
			<div class="container-hero">
				<div class="container hero">
					<div class="customer-support">
						<i class="fa-solid fa-headset"></i>
						<div class="content-customer-support">
							<span class="text">Soporte al cliente</span>
							<span class="number">123-456-7890</span>
						</div>
					</div>

					<div class="container-logo">
					<i class="fa-sharp fa-solid fa-laptop"></i>
						<h1 class="logo"><a href="/">MICROMASTERPIECES</a></h1>
					</div>

					<div class="container-user">
						<?php
						if (isset($_SESSION["username"])) {
						echo '<a class="welcome-message">Hola, ' . $_SESSION["username"] . '</a>';
						echo '<i class="fa-solid fa-user"></i>'	;
						}
						else
						{
						echo '<i class="fa-solid fa-user"></i>';		
						}
						?>

                        <!--Redireccion al carrito-->
                        <?php
                         if (isset($_SESSION["username"])) {
                            echo '<a href="carrito.php"> <i class="fa-solid fa-basket-shopping" ></i></a>';
                         }
                        
                        ?>
						
						
                        
                        
                        
                        <div class="content-shopping-cart">
							<span class="text">Carrito</span>
                            <!--codigo  php para el conteo del carrito-->
                        <?php
                            if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
                            $numArticulos = count($_SESSION['carrito']);
                            echo '<span class="number">' . $numArticulos . '</span>';
                            } else {
                                    echo '<span class="number">(0)</span>';
                            }
                        ?>
						</div>
					</div>
				</div>
			</div>
<div class="container-navbar">
				<nav class="navbar container">
					<i class="fa-solid fa-bars"></i>
					<ul class="menu">
						<li><a href="index.php">Inicio</a></li>
						<li><a href="catalogo_herr.php">Catalogo</a></li>
                        <!--Condicion en mi barra de navegacion-->
                        <?php
                            if (isset($_SESSION["username"])) 
                            {
                                // Verificar el rol del usuario
                                if ($_SESSION["rol"] == 1) {
                                // Si el ID de usuario es 1 (Administrador), msotar los controles de herramientas
                                echo '<li>
                                <a href="control_herr.php">Añadir Herramienta</a>
                                </li>';
                                echo '<li>
                                <a href="mod_herr.php">Modificar Herramienta</a>
                                </li>';
                                echo '<li action="logout.php" method="POST" id="logout-form">
                                <a type="submit" id="logout-button" class="button" href="logout.php" >Cerrar Sesión</a>
                                </li>'; 
                                } else {
                                // El usuario ha iniciado sesión, pero no es administrador
                                header("Location: index.php");       
                                }             
                            } else {
                                // El usuario no ha iniciado sesión, mostrar los botones de "Iniciar sesión" y "Registrarse"
                                echo '<li class="button"><a href="' . $iniciar_sesion_url . '">' . $iniciar_sesion_texto . '</a></li>';
                                echo '<li class="button"><a href="registrarse.php">Registrarse</a></li>';
                            }
                        ?>
						<!--li><a href="#">más</a></li-->
					</ul>
					<form class="search-form">
						<input type="search" placeholder="Buscar..." />
						<button class="btn-search">
							<i class="fa-solid fa-magnifying-glass"></i>
						</button>
					</form>
				</nav>
			</div>
		</header>
<body>
<br><br><br>
<div class="container">
    <div class="del-form">
    <div class="logo">
        <img src="imagenes/logo3.png" alt="Logo del negocio">
    </div>
        <h1>Eliminar Herramienta</h1>
        <form action="#" method="POST" id="del-form">
        <div class="form-group">
            <label for="id_herramienta">ID de la Herramienta a Eliminar:</label>
            <input type="text" name="id_herramienta" required>
        </div>
        <div class="form-group">
        <label for="admin_password">Contraseña de Administrador:</label>
        <input type="password" name="admin_password" required>
        </div>
        <div class="form-group">
            <button type="submit" id="delete-button" class="button">Eliminar Herramienta</button>
        </div>
        </form>
    </div>
</div>
</body>
<script
			src="https://kit.fontawesome.com/81581fb069.js"
			crossorigin="anonymous"
		></script>
</html>
