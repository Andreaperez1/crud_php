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