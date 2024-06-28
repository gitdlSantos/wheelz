<?php
require_once 'curl_helper.php';

$restAPIBaseURL = 'http://localhost/api_restful2/api.php';

// Función para obtener y mostrar todos los posts
function listarPosts($restAPIBaseURL) {
    $data = json_decode(file_get_contents($restAPIBaseURL . '/posts'), true);

    if (is_array($data)) {
        foreach ($data as $post) {
            echo "<p>ID: {$post['id_publi']} --- Contenido: {$post['contenido']} --- Fecha de creación: {$post['fecha_creacion']}</p>";
        }
    } else {
        echo "<p>No se encontraron posts.</p>";
    }
}

// Función para obtener y mostrar un post por ID
function listarPostPorID($restAPIBaseURL, $postId) {
    $data = json_decode(file_get_contents($restAPIBaseURL . '/posts/' . $postId), true);

    if (isset($data['response'])) {
        $post = json_decode($data['response'], true);
        if (is_array($post)) {
            echo "<p>ID: {$post['id_publi']} --- Contenido: {$post['contenido']} --- Fecha de creación: {$post['fecha_creacion']}</p>";
        } else {
            echo "<p>Post no encontrado.</p>";
        }
    } else {
        echo "<p>Post no encontrado.</p>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>API Restful Posts</title>
</head>

<body>
    <h1>API Restful Posts</h1>

    <h2>Listar Posts</h2>
    <form method="GET" action="index.php">
        <button type="submit" name="ListarPosts">Listar Posts</button>
    </form>

    <!-- Aquí se mostrarán los posts -->
    <?php
    if (isset($_GET['ListarPosts'])) {
        listarPosts($restAPIBaseURL);
    }
    ?>

    <h2>Listar Post por ID</h2>
    <form method="GET" action="index.php">
        <input type="number" name="postId" placeholder="Post ID" required>
        <button type="submit" name="ListarPostID">Listar Post</button>
    </form>

    <!-- Aquí se mostrarán los posts o mensajes de error -->
    <?php
    if (isset($_GET['ListarPostID'])) {
        $postId = $_GET['postId'];
        listarPostPorID($restAPIBaseURL, $postId);
    }
    ?>

    <h2>Agregar Post</h2>
    <form method="POST" action="index.php">
        <input type="text" name="contenido" placeholder="Contenido" required>
        <button type="submit" name="AgregarPost">Agregar Post</button>
    </form>
    <?php
    if (isset($_POST['AgregarPost'])) {
        $data = [
            'contenido' => $_POST['contenido']
        ];
        $response = sendRequest($restAPIBaseURL . '/posts', 'POST', $data);
        $response = json_decode($response, true);
        // Verificar si la respuesta tiene la estructura esperada
        if (isset($response['response'])) {
            echo "<p>Post agregado correctamente</p>";
        } else {
            echo "<p>Fallo en agregarse.</p>";
        }
    }
    ?>

    <h2>Modificar Post por ID</h2>
    <form method="POST" action="index.php">
        <input type="number" name="ModificarPostId" placeholder="Post ID" required>
        <input type="text" name="contenido" placeholder="Contenido">
        <button type="submit" name="ModificarPost">Modificar Post</button>
    </form>
    <?php
    if (isset($_POST['ModificarPost'])) {
        $postId = $_POST['ModificarPostId'];
        $data = [
            'contenido' => !empty($_POST['contenido']) ? $_POST['contenido'] : null
        ];
        $response = sendRequest($restAPIBaseURL . '/posts/' . $postId, 'PUT', $data);
        $response = json_decode($response, true);
    
        if ($response['http_code'] == 200) {
            echo "<p>Post modificado correctamente</p>";
        } else {
            echo "<p>Fallo al modificarse</p>";
        }
    }
    ?>

    <h2>Eliminar Post por ID</h2>
    <form method="POST" action="index.php">
        <input type="number" name="EliminarPostId" placeholder="Post ID" required>
        <button type="submit" name="EliminarPost">Eliminar Post</button>
    </form>
    <?php
    if (isset($_POST['EliminarPost'])) {
        $postId = $_POST['EliminarPostId'];
        $response = sendRequest($restAPIBaseURL . '/posts/' . $postId, 'DELETE');
        $response = json_decode($response, true);
    
        if ($response['http_code'] == 200) {
            echo "<p>Post eliminado correctamente</p>";
        } else {
            echo "<p>Fallo al eliminar el post</p>";
        }
    }
    ?>
</body>

</html>
