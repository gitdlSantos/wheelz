<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "usuarios";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$email = $_POST['mail_login'];
$pass = $_POST['pass_login'];

// Preparar la consulta
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $username, $hashed_password);
    $stmt->fetch();

    if (password_verify($pass, $hashed_password)) {
        $_SESSION["name"] = $username;
        header("Location: index.html");
        exit();
    } else {
        echo "El usuario o la contraseña son incorrectos";
    }
} else {
    echo "El usuario o la contraseña son incorrectos";
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
