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
if (isset($_POST["eliminar_seleccionados"])) {
    if (isset($_POST["eliminar_producto"]) && is_array($_POST["eliminar_producto"])) {
        foreach ($_POST["eliminar_producto"] as $producto_id) {
            // Verificar si el producto existe en el carrito antes de eliminarlo
            if (isset($_SESSION["carrito"][$producto_id])) {
                // Eliminar el producto del carrito
                unset($_SESSION["carrito"][$producto_id]);
            }
        }
    }
    // Redirigir nuevamente a la página del carrito
    header("Location: carrito.php");
    exit();
}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles_carrito.css">
    <link rel="stylesheet" href="./styles/stylesindex.css">
    <link rel="shortcut icon" href="./imagenes/mico.ico">
    <title>Resumen del Carrito</title>
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
<body>

<main>
    <section class="container">
        <h1>Resumen del Carrito</h1>
        <form action="#" method="POST">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($_SESSION["carrito"]) && is_array($_SESSION["carrito"])) {
                            foreach ($_SESSION["carrito"] as $producto_id => $producto) {
                                echo '<tr>';
                                echo '<td>' . $producto['nombre'] . '</td>';
                                echo '<td><img src="data:image/jpeg;base64,' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '" witdh="200" height="200">';
                                echo '<td>' . $producto['descripcion'] . '</td>';
                                echo '<td>$' . $producto['precio'] . '</td>';
                                echo '<td>';
                                echo '<input type="checkbox" name="eliminar_producto[]" value="' . $producto_id . '">'; // Checkbox
                                echo '</td>';
                                echo '</tr>';
                            }
                        
                        } else {
                            echo '<p>El carrito está vacío.</p>';
                        }
                        
                    ?>
                </tbody>
            </table>
            <?php 
             echo '<button type="submit" name="eliminar_seleccionados">Eliminar Seleccionados</button>';
             echo '</form>';
                   $total = 0;
            if (isset($_SESSION["carrito"]) && is_array($_SESSION["carrito"])) {
                // Calcular el total
                foreach ($_SESSION["carrito"] as $producto_id => $producto) {
                    $total += $producto['precio'];
                }
            
            }
            
            ?>
            <form action="procesar_pago.php" method="POST">
            <div class="total">
                <p>Total: <?php echo $total; ?></p>
            </div>

            <button type="submit" class="btn btn-primary">Pagar</button>
        </form>
    </section>
</main>
<script
			src="https://kit.fontawesome.com/81581fb069.js"
			crossorigin="anonymous"
		></script>
</body>
</html>
