<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require_once '../Conexion/conexion.php';

        // Recibir datos del formulario
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $clave = $_POST['clave'];
        $confirmar_clave = $_POST['confirmar_clave'];

        // Validar que el correo electrónico tenga un formato válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Correo electrónico no válido.');</script>";
        } else if ($clave !== $confirmar_clave) {
            // Validar que las contraseñas coincidan
            echo "<script>alert('Las contraseñas no coinciden.');</script>";
        } else {
            // Encriptar la contraseña
            $hashed_password = password_hash($clave, PASSWORD_DEFAULT);

            try {
                // Preparar y ejecutar la consulta
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, clave) VALUES (?, ?, ?)");
                $stmt->bindParam(1, $nombre);
                $stmt->bindParam(2, $email);
                $stmt->bindParam(3, $hashed_password);

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Registro exitoso. Serás redirigido a la página de inicio de sesión.');
                            window.location.href = 'index.html';
                          </script>";
                } else {
                    echo "<script>alert('Error al registrar el usuario.');</script>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }

            $pdo = null;
        }
    }
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nintengames - Registro</title>
    <link rel="stylesheet" href="css/master.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff4081, #81d4fa);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .register {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }
        h1 {
            font-size: 24px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        form {
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        
        input {
            margin-bottom: 20px;
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        
        button {
            padding: 15px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        .login-link {
            margin-top: 15px;
            text-align: center;
        }
        
        .login-link a {
            color: #007BFF;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <main class="register">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h1>Formulario de Registro.</h1>
            <input type="text" name="nombre" placeholder="Nombre Completo" required>
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <input type="password" name="confirmar_clave" placeholder="Confirmar Contraseña" required>
            <button type="submit">Registrarse</button>
            <div class="login-link">
                <a href="index.html">¿Ya tienes una cuenta? Inicia sesión</a>
            </div>
        </form>
    </main>

  
</body>

</html>
