<?php

class User {
    private $conn;
    private $table_name = "users";

    private $id;
    private $name;
    private $email;
    private $password;
    private $profile_picture; // Adiciona a propriedade para a foto de perfil

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($name, $email, $password) {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, password=:password";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));
        $password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function login($email, $password) {
        $query = "SELECT id, name, email, password FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(":email", $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $password_hash = $row['password'];

            if (password_verify($password, $password_hash)) {
                return true;
            }
        }

        return false;
    }

    // Método para obter usuário por ID
    public function getUserById($id) {
        $query = "SELECT id, name, email, profile_picture FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->name, $this->email, $this->profile_picture);
        
        if ($stmt->fetch()) {
            return true;
        }

        return false;
    }

    // Método para obter o ID do usuário
    public function getId() {
        return $this->id;
    }

    // Método para obter o nome do usuário
    public function getName() {
        return $this->name;
    }

    // Método para obter o email do usuário
    public function getEmail() {
        return $this->email;
    }

    // Método para obter o caminho da foto de perfil
    public function getProfilePicture() {
        return $this->profile_picture;
    }
}
?>
