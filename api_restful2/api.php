<?php
require_once 'config.php';
require_once 'post.php';

$postObj = new Post($conn);

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_SERVER['PATH_INFO'];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        if ($endpoint === '/posts') {
            $posts = $postObj->listPosts();
            echo json_encode($posts);
        } elseif (preg_match('/^\\/posts\\/(\\d+)$/', $endpoint, $matches)) {
            $postId = $matches[1];
            $post = $postObj->getPostById($postId);
            echo json_encode(['response' => json_encode($post)]);
        }
        break;
    case 'POST':
        if ($endpoint === '/posts') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $postObj->addPost($data);
            echo json_encode(['success' => $result, 'http_code' => $result ? 200 : 400]);
        }
        break;
    case 'PUT':
        if (preg_match('/^\\/posts\\/(\\d+)$/', $endpoint, $matches)) {
            $postId = $matches[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $postObj->updatePost($postId, $data);
            echo json_encode(['success' => $result === true, 'error' => $result !== true ? $result : null, 'http_code' => $result === true ? 200 : 400]);
        }
        break;
    case 'DELETE':
        if (preg_match('/^\\/posts\\/(\\d+)$/', $endpoint, $matches)) {
            $postId = $matches[1];
            $result = $postObj->deletePost($postId);
            echo json_encode(['success' => $result, 'http_code' => $result ? 200 : 400]);
        }
        break;
}
?>