<?php
// Requerir el archivo de conexión a la BD
require_once '../Conexion/conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    echo "<script>alert('ID de juego inválido.'); window.location.href = 'dashboard.php';</script>";
    exit;
}

// Consultar la información del juego
$consultaJuego = $pdo->prepare("SELECT games.*, platforms.name AS platform_name, categories.name AS category_name FROM games JOIN platforms ON games.platform_id = platforms.id JOIN categories ON games.category_id = categories.id WHERE games.id = :id");
$consultaJuego->execute(['id' => $id]);
$juego = $consultaJuego->fetch(PDO::FETCH_ASSOC);

if (!$juego) {
    echo "<script>alert('Juego no encontrado.'); window.location.href = 'dashboard.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nintengames - Show</title>
    <link rel="stylesheet" href="css/master.css">
    <style>
        .photo-preview img {
            width: 300px; 
            height: auto;
        }
    </style>
</head>

<body>
    <main class="show">
        <header>
            <h2>Consultar VideoJuego</h2>
            <a href="dashboard.php" class="back"></a>
            <a href="index.html" class="close"></a>
        </header>
        <figure class="photo-preview">
            <img src="../upload/<?php echo htmlspecialchars($juego['foto']); ?>" alt="<?php echo htmlspecialchars($juego['title']); ?>">
        </figure>
        <div>
            <br>
            <article class="info-title">
                <p><?php echo htmlspecialchars($juego['title']); ?></p>
            </article>
            <article class="info-platform">
                <p><?php echo htmlspecialchars($juego['platform_name']); ?></p>
            </article>
            <article class="info-category">
                <p><?php echo htmlspecialchars($juego['category_name']); ?></p>
            </article>
            <article class="info-year">
                <p><?php echo htmlspecialchars($juego['year']); ?></p>
            </article>
        </div>
    </main>
</body>

</html>