<?php
// Requerir el archivo de conexión a la BD
require_once '../Conexion/conexion.php';

// Obtener el ID del juego desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar la información del juego
$consultaJuego = $pdo->prepare("SELECT * FROM games WHERE id = :id");
$consultaJuego->execute(['id' => $id]);
$juego = $consultaJuego->fetch(PDO::FETCH_ASSOC);

// Consultar las plataformas y categorías desde la base de datos
$consultaPlataformas = $pdo->query("SELECT * FROM platforms");
$plataformas = $consultaPlataformas->fetchAll(PDO::FETCH_ASSOC);

$consultaCategorias = $pdo->query("SELECT * FROM categories");
$categorias = $consultaCategorias->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $title = $_POST['title'];
    $platform = $_POST['platform'];
    $category = $_POST['category'];
    $year = $_POST['year'];

    // Manejar la foto
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto'];
        $fotoName = $foto['name'];
        $fotoTmpName = $foto['tmp_name'];
        $fotoError = $foto['error'];

        // Ruta donde se almacenará la foto (cambia "carpeta_destino" por la ruta real)
        $fotoDestination = '../upload/' . $fotoName;

        // Mover la foto al destino
        move_uploaded_file($fotoTmpName, $fotoDestination);
    } else {
        // Mantener la foto actual si no se sube una nueva
        $fotoName = $juego['foto'];
    }

    // Preparar la consulta SQL de actualización
    $consulta = $pdo->prepare("UPDATE games SET title = :title, platform_id = :platform, category_id = :category, year = :year, foto = :foto WHERE id = :id");

    // Asociar los valores con los parámetros de la consulta
    $consulta->bindParam(':title', $title);
    $consulta->bindParam(':platform', $platform);
    $consulta->bindParam(':category', $category);
    $consulta->bindParam(':year', $year);
    $consulta->bindParam(':foto', $fotoName); 
    $consulta->bindParam(':id', $id);

    // Ejecutar la consulta
    if ($consulta->execute()) {
        echo "<script>alert('El juego se ha actualizado correctamente.'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el juego.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nintengames - Edit</title>
    <link rel="stylesheet" href="css/master.css">
    <style>
        .photo-preview img {
            width: 300px; 
            height: auto;
        }
    </style>
</head>
<body>
    <main class="edit">
        <header>
            <h2>Modificar VideoJuego</h2>
            <a href="dashboard.php" class="back"></a>
            <a href="index.html" class="close"></a>
        </header>
        <figure class="photo-preview">
            <img src="../upload/<?php echo htmlspecialchars($juego['foto']); ?>" alt="<?php echo htmlspecialchars($juego['title']); ?>">
        </figure>
        <form enctype="multipart/form-data" method="post" action="">
            <input type="text" name="title" placeholder="Title" value="<?php echo htmlspecialchars($juego['title']); ?>" required>
            <div class="select">
                <select name="platform" required>
                    <option value="">Seleccione Consola...</option>
                    <?php foreach ($plataformas as $plataforma) : ?>
                        <option value="<?php echo $plataforma['id']; ?>" <?php echo ($plataforma['id'] == $juego['platform_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($plataforma['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="select">
                <select name="category" required>
                    <option value="">Seleccione Categoría...</option>
                    <?php foreach ($categorias as $categoria) : ?>
                        <option value="<?php echo $categoria['id']; ?>" <?php echo ($categoria['id'] == $juego['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="button" class="upload">Subir Portada</button>
            <input type="file" name="foto" accept="image/*" style="display: none;">
            <input type="text" name="year" placeholder="Year" value="<?php echo htmlspecialchars($juego['year']); ?>" required>
            <button type="submit" class="update">Modificar</button>
        </form>
    </main>
    <script>
        document.querySelector('.upload').addEventListener('click', () => {
            document.querySelector('input[name="foto"]').click();
        });
    </script>
</body>
</html>
