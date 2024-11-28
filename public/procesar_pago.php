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

// Función para verificar si un producto está en el carrito
function estaEnCarrito($producto_id) {
    return isset($_SESSION["carrito"][$producto_id]);
}

// Información de los cursos (simulando información de la base de datos)
$cursos = array(
    1 => array(
        'nombre' => 'Curso de Armado de Computadoras',
        'descripcion' => 'Aprende a armar y ensamblar tu propia computadora.',
        'precio' => 199.99,
    ),
    // Agrega más cursos si es necesario
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles_catalogo.css">
    <script src="script.js"></script>
    <link rel="stylesheet" href="./styles/stylesindex.css">
    <link rel="shortcut icon" href="./imagenes/mico.ico">
    <script
			src="https://kit.fontawesome.com/81581fb069.js"
			crossorigin="anonymous"
		></script>
    <title>Resumen de Compra</title>
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
						<li><a href="#">Inicio</a></li>
						<li><a href="catalogo_herr.php">Catalogo</a></li>
                        <li><a href="#">Contactanos</a></li>
                        <!--Condicion en mi barra de navegacion-->
                        <?php
                            if (isset($_SESSION["username"])) 
                            {
                                // Verificar el rol del usuario
                                if ($_SESSION["rol"] == 1) {
                                // Si el ID de usuario es 1 (Administrador), mostrar el botón "Control de Herramientas"
                                echo '<li action="control_herr.php" method="POST" id="tools-form">
                                <a type="submit" id="tools-button" class="button">Control de Herramientas</a>
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
<body>    

<main>
    <div class="container">
        <h1>Resumen de Compra</h1>

        <?php
        if (isset($_SESSION["carrito"]) && is_array($_SESSION["carrito"]) && count($_SESSION["carrito"]) > 0) {
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Curso</th>';
            echo '<th>Precio</th>';
            echo '<th>Eliminar</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $total = 0;

            foreach ($_SESSION["carrito"] as $curso_id => $curso) {
                $nombre = $curso['nombre'];
                $precio = $curso['precio'];
                $total += $precio;

                echo '<tr>';
                echo '<td>' . $nombre . '</td>';
                echo '<td>$' . $precio . '</td>';
                echo '<td><a href="eliminar_curso.php?id=' . $curso_id . '" class="btn btn-danger">Eliminar</a></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '<h3>Total: $' . $total . '</h3>';

            // Agrega aquí el formulario de pasarela de pago (por ejemplo, PayPal o Stripe)

            echo '<div id="payment_options"></div>';
            echo '<div class="col-sm"></div>';
        } else {
            echo '<p>Tu carrito está vacío.</p>';
        }
        ?>

    </div>
</main>
</body>
</html>
