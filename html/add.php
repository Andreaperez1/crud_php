<?php
require_once '../Conexion/conexion.php';

// Obtener las plataformas desde la base de datos
$consultaPlataformas = $pdo->query("SELECT * FROM platforms");
$plataformas = $consultaPlataformas->fetchAll(PDO::FETCH_ASSOC);

// Obtener las categorías desde la base de datos
$consultaCategoria = $pdo->query("SELECT * FROM categories");
$categorias = $consultaCategoria->fetchAll(PDO::FETCH_ASSOC);

$msg = '';

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $title = $_POST['title'];
    $platform = $_POST['platform'];
    $category = $_POST['category'];
    $year = $_POST['year'];

    // Manejar la foto
    $foto = $_FILES['foto'];
    $fotoName = $foto['name'];
    $fotoTmpName = $foto['tmp_name'];
    $fotoError = $foto['error'];

    // Ruta donde se almacenará la foto (cambia "carpeta_destino" por la ruta real)
    $fotoDestination = '../upload/' . $fotoName;

    // Mover la foto al destino
    if (move_uploaded_file($fotoTmpName, $fotoDestination)) {
        $consulta = $pdo->prepare("INSERT INTO games (foto, title, platform_id, category_id, year) VALUES (:foto, :title, :platform, :category, :year)");

        $consulta->bindParam(':title', $title);
        $consulta->bindParam(':platform', $platform);
        $consulta->bindParam(':category', $category);
        $consulta->bindParam(':year', $year);
        $consulta->bindParam(':foto', $fotoName);  

        // Ejecutar la consulta
        if ($consulta->execute()) {
            $msg = "El juego se ha guardado correctamente.";
        } else {
            $msg = "Error al guardar el juego.";
        }
    } else {
        $msg = "Error al subir la foto.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nintengames - Add</title>
    <link rel="stylesheet" href="css/master.css">
</head>

<body>
    <main class="add">
        <header>
            <h2>Adicionar VideoJuego</h2>
            <a href="dashboard.php" class="back"></a>
            <a href="index.html" class="close"></a>
        </header>
        <?php if ($msg) : ?>
            <script>
                alert("<?php echo $msg; ?>");
                setTimeout(function() {
                    window.location.href = "dashboard.php";
                }, 3000); // Redireccionar después de 3 segundos
            </script>
        <?php endif; ?>
        <figure class="photo-preview">
            <img src="images/photo-lg-0.svg" alt="">
        </figure>
        <form enctype="multipart/form-data" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="title" placeholder="Title" required>
            <div class="select">
                <select name="platform" required>
                    <option value="">Seleccione Consola...</option>
                    <?php foreach ($plataformas as $plataforma) : ?>
                        <option value="<?php echo $plataforma['id']; ?>"><?php echo $plataforma['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="select">
                <select name="category" required>
                    <option value="">Seleccione Categoría...</option>
                    <?php foreach ($categorias as $category) : ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="button" class="upload">Subir Portada</button>
            <input type="file" name="foto" accept="image/*" style="display: none;" required>
            <input type="text" name="year" placeholder="Year" required>
            <button type="submit" class="save">Guardar</button>
        </form>
    </main>
    <script>
        document.querySelector('.upload').addEventListener('click', () => {
            document.querySelector('input[name="foto"]').click();
        });
    </script>
</body>

</html>