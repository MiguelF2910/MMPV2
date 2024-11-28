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

// Incluir la conexión a la base de datos
require('conexion.php');

// Verificar si se ha enviado el nombre o el ID de la herramienta a modificar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_o_id"])) {
    $nombre_o_id = $_POST["nombre_o_id"];

    // Realizar la consulta para obtener la información de la herramienta
    $sql = "SELECT nombre, descripcion, imagen, precio, existencias FROM herramientas WHERE nombre = '$nombre_o_id' OR id = '$nombre_o_id'";
    $result = $mysqli->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $nombre = $row['nombre'];
        $descripcion = $row['descripcion'];
        $imagen = base64_encode($row['imagen']); // La imagen se almacena en formato BLOB, así que necesitas convertirla a base64
        $precio = $row['precio'];
        $existencias = $row['existencias'];
    } else {
        echo "Error al consultar la base de datos: " . $mysqli->error;
    }
}

// Verificar si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre"]) && isset($_POST["descripcion"]) && isset($_POST["precio"]) && isset($_FILES["imagen"]) && isset($_POST["existencias"])) {
    // Recuperar los datos del formulario
    $nuevo_nombre = $_POST["nombre"];
    $nueva_descripcion = $_POST["descripcion"];
    $nuevo_precio = $_POST["precio"];
    $nuevas_existencias = $_POST["existencias"];

    // Inicializar la variable para la nueva imagen
    $nueva_imagen = null;

    // Verificar si se ha seleccionado una nueva imagen
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["size"] > 0) {
        // Procesa la nueva imagen
        $nueva_imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    }

    // Realizar la actualización en la base de datos
    $sql = "UPDATE herramientas SET nombre = '$nuevo_nombre', descripcion = '$nueva_descripcion', precio = '$nuevo_precio', existencias = '$nuevas_existencias'";

    // Agregar la actualización de la imagen solo si se ha seleccionado una nueva
    if ($nueva_imagen !== null) {
        $sql .= ", imagen = '$nueva_imagen'";
    }

    $sql .= " WHERE nombre = '$nombre'";

    if ($mysqli->query($sql) === TRUE) {
        echo "Herramienta modificada exitosamente.";
    } else {
        echo "Error al modificar la herramienta: " . $mysqli->error;
    }
}
// Cerrar la conexión a la base de datos
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo_modherr.css">
    <link rel="stylesheet" href="stylesindex.css">
    <title>Modificar Herramienta</title>
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
    <?php
    if (isset($nombre)) {
        // Mostrar el formulario prellenado con los detalles de la herramienta
        echo '<div class="mod-form">';
        echo '<div class="logo">';
        echo '<img src="imagenes/logo3.png" alt="Logo del negocio">';
        echo '</div>';
        echo '<h1>Modificar Herramienta</h1>';
        echo '<form action="#" method="POST" enctype="multipart/form-data">';
        echo '<input type="hidden" name="nombre_o_id" value="' . $nombre_o_id . '">';
        echo '<div class="form-group">';
        echo '<label for="nombre">Nombre de la Herramienta</label>';
        echo '<input type="text" class="form-control" name="nombre" placeholder="Nombre de la Herramienta" value="' . $nombre . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="descripcion">Descripción</label>';
        echo '<textarea id="descripcion" class="form-control" name="descripcion" placeholder="Descripción">' . $descripcion . '</textarea>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="imagen">Cargar Nueva Imagen (Opcional)</label>';
        echo '<input type="file" name="imagen" accept="image/*">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="precio">Precio</label>';
        echo '<input type="number" name="precio" step="0.01" value="' . $precio . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="existencias">Cantidad en Existencias</label>';
        echo '<input type="number" name="existencias" step="1" value="' . $existencias . '">';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary">Guardar Cambios</button>';
        echo '</form>';
        echo '</div>';
    } else {
        // Mostrar el formulario para ingresar el nombre o ID de la herramienta a modificar
        echo '<div class="mod-form">';
        echo '<div class="logo">';
        echo '<img src="imagenes/logo3.png" alt="Logo del negocio">';
        echo '</div>';
        echo '<h1>Modificar Herramienta</h1>';
        echo '<form action="#" method="POST">';
        echo '<div class="form-group">';
        echo '<label for="nombre_o_id">Nombre o ID de la Herramienta</label>';
        echo '<input type="text" class="form-control" name="nombre_o_id" placeholder="Nombre o ID de la Herramienta">';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary">Buscar Herramienta</button>';
        echo '</form>';
        echo '</div>';
    }
    ?>
</div>
</body>
<script
			src="https://kit.fontawesome.com/81581fb069.js"
			crossorigin="anonymous"
		></script>
</html>

