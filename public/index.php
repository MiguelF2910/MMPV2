<?php
// Iniciar la sesión
session_start();
require('conexion.php');
$sql = "SELECT id, nombre, descripcion, imagen, precio FROM herramientas LIMIT 4";
    $result = $mysqli->query($sql);

// Verificar si el usuario ha iniciado sesión
$iniciar_sesion_texto = "Iniciar sesión";
$iniciar_sesion_url = "login.php"; //Redireccionamiento a login

if (isset($_SESSION["username"])) {
    // El usuario ha iniciado sesión
    $iniciar_sesion_texto = "Cerrar sesión";
    $iniciar_sesion_url = "logout.php"; 

}


if (isset($_SESSION["username"])) {
	$username= $_SESSION["username"];
	$sql = "SELECT rol FROM usuarios WHERE username=?";

	$stmt = $mysqli->prepare($sql);

		if($stmt)
		{
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows > 0)
			{
				$stmt->bind_result($rol);
				if($stmt->fetch())
				{
					$_SESSION["rol"] = $rol;
				}
				else{
					echo "No hay valores obtenidos";
				}
				$stmt->close();


			}
			else{echo "erro de consulta",$mysqli->error;
			}

		}

}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1.0"
		/>
		<title>MICROMASTERPIECES</title>
		<link rel="stylesheet" href="./styles/stylesindex.css" />
		<link rel="shortcut icon" href="./imagenes/mico.ico">
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
						echo '<a href="login.php"><i class="fa-solid fa-user"></i></a>';		
						}
						?>
						<a href="carrito.php"><i class="fa-solid fa-basket-shopping"></i></a>
						<div class="content-shopping-cart">
							<span class="text">Carrito</span>
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
						<!--li><a href="#">Inicio</a></li
						Esta sera comentada puesto que no me redirige a nada 
						>
						<li><a href="#">Quienes somos</a></li>-->
						<li><a href="catalogo_herr.php">Nuestro catalogo</a></li>



						<li>
							<?php
							if(isset($_SESSION["rol"]))
							{
								$rol =$_SESSION["rol"];
								if($rol == 1)
								{

									echo '<a href="control_herr.php">Mis herramientas</a>';
								}
								
							}
							
							?>						
						</li>
						
						
						<li><a href="catalogo_herr.php">Cursos</a></li>
						<li><a href="chatui.php">Nuevo chatbot</a></li>
						<li><a href="#">Contactanos</a></li>
						<!--li><a href="#">más</a></li-->
						<li>
							<?php
							if (isset($_SESSION["username"])) {
								echo '<a href="logout.php">Cerrar sesión</a>';
							}
							else{
								echo '<a href="' . $iniciar_sesion_url . '">' . $iniciar_sesion_texto . '</a>';
								echo '<li><a href="registrarse.php">Registrarse</a></li>';
							}
							?>
						</li>
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


		<!-- Codigo perteneciente a nuestro baner-->


		<section class="banner">
			<div class="content-banner">
				<p>MICROMASTERPIECES</p>
				<h2>Venta de refacciones de pc's<br />Ensamble y reparaciones</h2>
				<?php
				if (isset($_SESSION["username"])) {
			  		echo'<a href="catalogo_herr.php" method="POST" id="logout-form">Ver Herramientas</a>';
				}else{
					echo '<p>Por favor de iniciar sesion</p>';
				}
				?>			


				<!--a href="<?php echo $iniciar_sesion_url; ?>">Ver tienda</a-->
			</div>
		</section>



		<main class="main-content">
			<section class="container container-features">
				<div class="card-feature">
					<i class="fa-solid fa-location-dot"></i>
					<div class="feature-content">
						<span>Entrega de pedidos a domicilio</span>
						<p>Indicanos tu dirección e iremos para allá</p>
					</div>
				</div>
				<div class="card-feature">
					<i class="fa-solid fa-wallet"></i>
					<div class="feature-content">
						<span>Descuentos por nuestros productos</span>
						<p>La mejor calidad a precios bajos</p>
					</div>
				</div>
				<div class="card-feature">
					<i class="fa-solid fa-file"></i>
					<div class="feature-content">
						<span>Cursos con certificado</span>
						<p>Llenate de conocimientos</p>
					</div>
				</div>
				<div class="card-feature">
					<i class="fa-solid fa-headset"></i>
					<div class="feature-content">
						<span>Servicio al cliente 24/7</span>
						<p>LLámenos 24/7 al 123-456-7890</p>
					</div>
				</div>
			</section>

			<section class="container top-categories">
				<h1 class="heading-1">Nuestros productos</h1>
				<div class="container-categories">
					<div class="card-category category-moca">
						<p>Gama alta</p>
						<span>Ver más</span>
					</div>
					<div class="card-category category-expreso">
						<p>Gama media</p>
						<span>Ver más</span>
					</div>
					<div class="card-category category-capuchino">
						<p>Gama baja</p>
						<span>Ver más</span>
					</div>
				</div>
			</section>

			<section class="container top-products">
				<h1 class="heading-1">Más productos y servicios</h1>

				<div class="container-options">
					<span class="active">Destacados</span>
					<span>Más recientes</span>
					<span>Mejores Vendidos</span>
				</div>

				<section class="container-products">
        <?php
        // Iterar sobre los productos y mostrarlos dinámicamente
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="card-product">';
            echo '<div class="container-img">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['imagen']) . '" alt="' . $row['nombre'] . '" />';
            echo '</div>';
            echo '<div class="content-card-product">';
            echo '<h3>' . $row['nombre'] . '</h3>';
            echo '<span class="add-cart"><i class="fa-solid fa-basket-shopping"></i></span>';
            echo '<p class="price">$' . $row['precio'] . '</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </section>
	</main>

			
		<footer class="footer">
			<div class="container container-footer">
				<div class="menu-footer">
					<div class="contact-info">
						<p class="title-footer">Información de Contacto</p>
						<ul>
							<li>
								Dirección: Av. Té 190, Col. Granjas México, Iztacalco, CDMX.
							</li>
							<li>Teléfono: 55 6541 6647</li>
							<li>Email: contacto@micrompieces.mx</li>
						</ul>
						<div class="social-icons">
							<span class="facebook">
								<i class="fa-brands fa-facebook-f"></i>
							</span>
							<span class="twitter">
								<i class="fa-brands fa-twitter"></i>
							</span>
							<span class="youtube">
								<i class="fa-brands fa-youtube"></i>
							</span>
							<span class="pinterest">
								<i class="fa-brands fa-pinterest-p"></i>
							</span>
							<span class="instagram">
								<i class="fa-brands fa-instagram"></i>
							</span>
						</div>
					</div>

					<div class="information">
						<p class="title-footer">Información</p>
						<ul>
							<li><a href="#">Acerca de Nosotros</a></li>
							<li><a href="#">Politicas de Privacidad</a></li>
							<li><a href="#">Términos y condiciones</a></li>
							<li><a href="#">Contactános</a></li>
						</ul>
					</div>

					<div class="my-account">
						<p class="title-footer">Mi cuenta</p>

						<ul>
							<li><a href="#">Mi cuenta</a></li>
							<li><a href="#">Historial de ordenes</a></li>
							<li><a href="#">Lista de deseos</a></li>
							<li><a href="#">Boletín</a></li>
							<li><a href="#">Reembolsos</a></li>
						</ul>
					</div>
				</div>

				<div class="copyright">
					<p>
						MicroMasterPieces &copy; 2022
					</p>

					<img src="./imagenes/payment.png" alt="Pagos">
				</div>
			</div>
		</footer>



    <script src="sc.js">
		
	</script> <!-- Enlace al JavaScript del chatbot -->
		<script
			src="https://kit.fontawesome.com/81581fb069.js"
			crossorigin="anonymous"
		></script>
	</body>
</html>
