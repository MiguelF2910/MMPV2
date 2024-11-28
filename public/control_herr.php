<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
$iniciar_sesion_texto = "Iniciar sesión";
$iniciar_sesion_url = "login.php"; //Redireccionamiento a login

if (isset($_SESSION["username"])) {
    // El usuario ha iniciado sesión
    $iniciar_sesion_texto = "Cerrar sesión";
    $iniciar_sesion_url = "logout.php"; 
}

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre"]) && isset($_POST["descripcion"]) && isset($_POST["precio"]) && isset($_FILES["imagen"]) && isset($_POST["existencias"])) {
    //Recupera los datos del formulario 
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $existencias = $_POST["existencias"];

    // Procesa la imagen
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));

    //Inlcuir la conexion a la base de datos
    require('conexion.php');

    //Realizar la consulta
    $sql = "INSERT INTO herramientas (nombre, descripcion, imagen, precio, existencias) VALUES ('$nombre','$descripcion','$imagen','$precio','$existencias')";

    // Ejecutar la consulta
    if ($mysqli->query($sql) === TRUE) {
        echo "Registro exitoso. Los datos han sido insertados en la base de datos.";
    } else {
        echo "Error al registrar los datos: " . $mysqli->error;
    }

    // Cerrar la conexión a la base de datos
    $mysqli->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles/estilo_addherr.css">
    <link rel="stylesheet" href="./styles/stylesindex.css">
    <title>Catalogo de Herramientas</title>
    <link rel="shortcut icon" href="./imagenes/mico.ico">
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
                                <a href="mod_herr.php">Modificar Herramienta</a>
                                </li>';
                                echo '<li>
                                <a href="del_herr.php">Eliminar Herramienta</a>
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
    <div class="add-form">
    <div class="logo">
        <img src="imagenes/logo3.png" alt="Logo del negocio">
    </div>
        <h1>Agregar Herramientas</h1>
        <form action="#" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nombre">Nombre de la Herramienta</label>
        <input type="text" class="form-control" name="nombre" placeholder="Nombre de la Herramienta">
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" class="form-control" name="descripcion" placeholder="Descripción"></textarea>
    </div>

    <div class="form-group">
        <label for="imagen">Imagen</label>
        <input type="file" name="imagen" accept="image/*" required>
    </div>

    <div class="form-group">
        <label for="precio">Precio</label>
        <input type="number" name="precio" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="existencias">Cantidad en Existencias</label>
        <input type="number" name="existencias" step="1" required>
    </div>
    <button type="submit" class="btn btn-primary" name="submit">Añadir Herramienta</button>
        </form>
        </div>
    </div>
</body>
<script
			src="https://kit.fontawesome.com/81581fb069.js"
			crossorigin="anonymous"
		></script>
</html>