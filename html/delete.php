<?php
// Requerir el archivo de conexión a la BD
require_once '../Conexion/conexion.php';

// Obtener el ID del juego a eliminar desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    echo "<script>alert('ID de juego inválido.'); window.location.href = 'dashboard.php';</script>";
    exit;
}

// Consultar la información del juego para mostrar en un mensaje de confirmación
$consultaJuego = $pdo->prepare("SELECT title FROM games WHERE id = :id");
$consultaJuego->execute(['id' => $id]);
$juego = $consultaJuego->fetch(PDO::FETCH_ASSOC);

if (!$juego) {
    echo "<script>alert('Juego no encontrado.'); window.location.href = 'dashboard.php';</script>";
    exit;
}

// Verificar si se ha enviado el formulario de confirmación de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar'])) {
    // Eliminar el juego de la base de datos
    $consultaEliminar = $pdo->prepare("DELETE FROM games WHERE id = :id");
    if ($consultaEliminar->execute(['id' => $id])) {
        echo "<script>alert('Juego eliminado correctamente.'); window.location.href = 'dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error al eliminar el juego.'); window.location.href = 'dashboard.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nintengames - Eliminar</title>
    <link rel="stylesheet" href="css/master.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .delete {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .confirm {
            border-top: 1px solid #ccc;
            padding-top: 20px;
            margin-top: 20px;
            text-align: right;
        }
        .confirmar, .cancelar {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .confirmar {
            background-color: #dc3545;
            color: #fff;
        }
        .confirmar:hover, .cancelar:hover {
            background-color: #c82333;
        }
        .cancelar {
            margin-right: 10px;
            background-color: #6c757d;
            color: #fff;
        }
    </style>
</head>
<body>
    <main class="delete">
        <header>
            <h2>Eliminar VideoJuego</h2>
            <a href="dashboard.php" class="back"></a>
            <a href="index.html" class="close"></a>
        </header>
        <div class="confirm">
            <p>¿Estás seguro de que deseas eliminar el juego "<?php echo htmlspecialchars($juego['title']); ?>"?</p>
            <form method="post" action="">
                <button type="submit" name="confirmar" class="confirmar">Confirmar</button>
                <a href="dashboard.php" class="cancelar">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>

