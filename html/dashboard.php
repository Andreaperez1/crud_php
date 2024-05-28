<?php
// Requerir el archivo de conexión a la BD
require_once '../Conexion/conexion.php';

// Obtener todos los juegos de la base de datos
$consultaJuegos = $pdo->query("SELECT games.id, games.title, games.foto, platforms.name AS platform_name FROM games JOIN platforms ON games.platform_id = platforms.id");
$juegos = $consultaJuegos->fetchAll(PDO::FETCH_ASSOC);

if (empty($juegos)) {
    echo "<script>alert('No hay juegos disponibles en la base de datos.'); window.location.href = 'dashboard.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nintengames - Dashboard</title>
    <link rel="stylesheet" href="css/master.css">
    <style>
        .cover img {
            width: 85px; 
            height: auto; 
        }
        .info {
            padding-left: 10px;
        }
        td {
            padding: 10px;
        }
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="main-container">
    <main class="dashboard">
        <header>
            <h2>Administrar VideoJuegos</h2>
            <a href="index.html" class="close"></a>
        </header>
        <a href="add.php" class="add"></a>   
        <table>
            <?php foreach ($juegos as $juego): ?>
            <tr>
                <td>
                    <figure class="cover">
                        <img src="../upload/<?php echo htmlspecialchars($juego['foto']); ?>" alt="<?php echo htmlspecialchars($juego['title']); ?>">
                    </figure>
                    <div class="info">
                        <h3><?php echo htmlspecialchars($juego['platform_name']); ?></h3>
                        <h4><?php echo htmlspecialchars($juego['title']); ?></h4>
                    </div>
                    <div class="controls">
                        <a href="show.php?id=<?php echo htmlspecialchars($juego['id']); ?>" class="show"></a>
                        <a href="edit.php?id=<?php echo htmlspecialchars($juego['id']); ?>" class="edit"></a>
                        <a href="delete.php?id=<?php echo htmlspecialchars($juego['id']); ?>" class="delete"></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
</div>
</body>
</html>
