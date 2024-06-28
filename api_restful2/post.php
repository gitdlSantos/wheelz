<?php
class Post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listPosts() {
        $sql = "SELECT * FROM Publicacion";
        $result = $this->conn->query($sql);
        $posts = array();

        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    }

    public function getPostById($id) {
        $sql = "SELECT * FROM Publicacion WHERE id_publi = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function addPost($data) {
        $contenido = $data['contenido'];
        $sql = "INSERT INTO Publicacion (contenido) VALUES ('$contenido')";
        return $this->conn->query($sql);
    }

    public function updatePost($id, $data) {
        $sql = "UPDATE Publicacion SET ";

        $fields = array();
        if (!empty($data['contenido'])) {
            $fields[] = "contenido = '{$data['contenido']}'";
        }

        if (count($fields) > 0) {
            $sql .= implode(", ", $fields);
            $sql .= " WHERE id_publi = $id";

            if ($this->conn->query($sql)) {
                return true;
            } else {
                return $this->conn->error;
            }
        } else {
            return false; // No fields to update
        }
    }

    public function deletePost($id) {
        $sql = "DELETE FROM Publicacion WHERE id_publi = $id";
        return $this->conn->query($sql);
    }
}
?>
