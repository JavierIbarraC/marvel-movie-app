<?php
// Definimos la URL de la API
const API_URL = "https://whenisthenextmcufilm.com/api";

$ch = curl_init(API_URL); // Iniciamos la sesión de cURL

// Configuramos cURL para que devuelva el resultado en lugar de mostrarlo en pantalla
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutamos la petición y guardamos el resultado
$response = curl_exec($ch);

// Verificamos si hubo errores en la solicitud
if (curl_errno($ch)) {
    echo 'Error en la solicitud: ' . curl_error($ch);
    exit;
}

// Decodificamos el JSON para tener un array asociativo
$data = json_decode($response, true);

// Cerramos la sesión de cURL
curl_close($ch);

// Verificamos que la respuesta sea válida
if (!isset($data['title']) || !isset($data['release_date']) || !isset($data['poster_url'])) {
    echo 'Error: No se pudieron obtener datos de la API.';
    echo $data;
    exit;
}

// API de OMDb para obtener la sinopsis
$apiKey = "TU_API_KEY"; // Reemplázalo con tu clave de OMDb
$title = urlencode($data['title']); // Usamos el título de la película
$omdbUrl = "http://www.omdbapi.com/?t={$title}&apikey={$apiKey}&plot=full";

$ch = curl_init($omdbUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$omdbResponse = curl_exec($ch);
curl_close($ch);

$omdbData = json_decode($omdbResponse, true);

// Verificar si OMDb devolvió una sinopsis
$synopsis = !empty($omdbData['Plot']) ? $omdbData['Plot'] : "No disponible";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La próxima película de Marvel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Marvel Movie Info</h1>
        <nav>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#info">Información</a></li>
            <li><a href="#contacto">Contacto</a></li>
        </ul>
    </nav>
    </header>
    <section id="info">
        <h2>La próxima película de Marvel</h2>
        <img src="<?= htmlspecialchars($data['poster_url']); ?>" alt="Poster de la película">
        <h3><?= htmlspecialchars($data['title']); ?></h3>
        <p>Se estrena el <?= date('d/m/Y', strtotime($data['release_date'])); ?></p>
        <p>Sinopsis: <?= htmlspecialchars($synopsis); ?></p>
        <button onclick="compartir()">Compartir</button>
    </section>
    <footer>
        <p>&copy; 2025 Marvel Movie Info. Todos los derechos reservados.</p>
    </footer>
    <script>
        function compartir() {
            alert("¡Comparte esta película con tus amigos!");
        }
    </script>
</body>
</html>