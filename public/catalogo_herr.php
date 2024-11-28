<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
$iniciar_sesion_texto = "Iniciar sesión";
$iniciar_sesion_url = "login.php"; // Redireccionamiento a la página de inicio de sesión

if (isset($_SESSION["username"])) {
    // El usuario ha iniciado sesión
    $iniciar_sesion_texto = "Cerrar sesión";
    $iniciar_sesion_url = "logout.php";
}

// Incluir la conexión a la base de datos
require('conexion.php');
// Verificar si se ha agregado un producto al carrito
if (isset($_POST["agregar_al_carrito"]) && isset($_POST["producto_id"])) {
    $producto_id = $_POST["producto_id"];
    
    // Obtener información del producto desde la base de datos
    $sql = "SELECT imagen, nombre, descripcion, precio FROM herramientas WHERE id = '$producto_id'";
    $result = $mysqli->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $nombre = $row['nombre'];
        $descripcion = $row['descripcion'];
        $precio = $row['precio'];
        $imagen = base64_encode($row['imagen']);

        // Agregar el producto al carrito (puedes guardar los productos en un arreglo en la sesión)
        $_SESSION["carrito"][$producto_id] = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'imagen' => $imagen,
        );
    }
    
    // Redirigir al carrito
    header("Location: catalogo_herr.php");
    exit();
}

// Función para verificar si un producto está en el carrito
function estaEnCarrito($producto_id) {
    return isset($_SESSION["carrito"][$producto_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Catalogo</title>   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles_catalogo.css">
    <link rel="stylesheet" href="./styles/stylesindex.css">
    <link rel="shortcut icon" href="./imagenes/mico.ico">
    <title>Catalogo de Herramientas</title>
</head>
<body>

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
						<li><a href="#">Catalogo</a></li>
                        <li><a href="#">Contactanos</a></li>
                        <!--Condicion en mi barra de navegacion-->
                        <?php
                            if (isset($_SESSION["username"])) 
                            {
                                // Verificar el rol del usuario
                                if ($_SESSION["rol"] == 1) {
                                // Si el ID de usuario es 1 (Administrador), mostrar el botón "Control de Herramientas"
                                echo '<li>
                                <a href="control_herr.php">Control de Herramientas</a>
                                </li>';
                                }
                                // El usuario ha iniciado sesión, mostrar el mensaje de bienvenida
                                echo '<li action="miscursos.php" method="POST" id="logout-form">
                                <a type="submit" id="logout-button" class="button">Mis Cursos</a>
                                </li>';
                                echo '<li action="logout.php" method="POST" id="logout-form">
                                <a type="submit" id="logout-button" class="button" href="logout.php" >Cerrar Sesión</a>
                                </li>';                            
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


<main>
    <?php 
    

    // Realizar la consulta para obtener las herramientas almacenadas
    $sql = "SELECT id, nombre, descripcion, imagen, precio FROM herramientas";
    $result = $mysqli->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $producto_id = $row['id'];
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $imagen = base64_encode($row['imagen']); // La imagen se almacena en formato BLOB, así que necesitas convertirla a base64
            $precio = $row['precio'];

            // Generar el HTML para mostrar la herramienta
            echo '<section class="jumbotron">';
            echo '<div class="texto">';
            echo '<h2>' . $nombre . '</h2>';
            echo '<p>' . $descripcion . '</p>';
            echo '<p>Precio: $' . $precio . '</p>';
            
            if (isset($_SESSION["username"])) {
                // Si el usuario ha iniciado sesión, mostrar el botón de "Comprar" solo si el producto no está en el carrito
                if (estaEnCarrito($producto_id)) {
                    echo '<p>Ya en el carrito</p>';
                } else {
                    echo '<form action="catalogo_herr.php" method="POST">';
                    echo '<input type="hidden" name="producto_id" value="' . $producto_id . '">';
                    echo '<button type="submit" name="agregar_al_carrito" class="btn">Comprar</button>';
                    echo '</form>';
                }
            }
            
            echo '</div>';
            echo '<div class="imagen">';
            echo '<img src="data:image/jpeg;base64,' . $imagen . '" alt="' . $nombre . '">';
            echo '</div>';
            echo '</section>';
        }

        // Liberar el resultado de la consulta
        $result->free();
    } else {
        echo "Error al consultar la base de datos: " . $mysqli->error;
    }

    // Cerrar la conexión a la base de datos
    $mysqli->close();
    ?>
</main>

        <script
			src="https://kit.fontawesome.com/81581fb069.js"
			crossorigin="anonymous"
		></script>

</body>
</html>
