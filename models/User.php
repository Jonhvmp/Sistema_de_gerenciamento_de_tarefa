<?php
// Author: Jonh Alex Paz de Lima
// All rights reserved

class User {
    private $conn;
    private $table_name = "users";

    private $id;
    private $name;
    private $email;
    private $password;
    private $profile_picture;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Função para validar o formato do email
    private function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Função para validar a existência do email (exemplo básico)
    private function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    public function register($name, $email, $password) {
        // Verifica se o formato do email é válido
        if (!$this->isValidEmail($email)) {
            return "Formato de email inválido.";
        }

        // Verifica se o email já existe
        if ($this->emailExists($email)) {
            return "Email já está em uso.";
        }

        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));
        $password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function login($email, $password) {
        $query = "SELECT id, name, email, password FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

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

    public function getUserById($id) {
        $query = "SELECT name, email, profile_picture FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $this->id = $id;
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->profile_picture = $row['profile_picture'];
            return true;
        }

        return false;
    }

    public function updateProfile($id, $name, $email) {
        // Verifica se o formato do email é válido
        if (!$this->isValidEmail($email)) {
            return "Formato de email inválido.";
        }

        $query = "UPDATE " . $this->table_name . " SET name = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));

        $stmt->bind_param("ssi", $name, $email, $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function changePassword($id, $current_password, $new_password, $confirm_password) {
        if ($new_password !== $confirm_password) {
            return false;
        }

        $query = "SELECT password FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $password_hash = $row['password'];

            if (password_verify($current_password, $password_hash)) {
                $new_password = htmlspecialchars(strip_tags($new_password));
                $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

                $query = "UPDATE " . $this->table_name . " SET password = ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("si", $new_password_hash, $id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function updateProfilePicture($id, $file_path) {
        $query = "UPDATE " . $this->table_name . " SET profile_picture = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $file_path = htmlspecialchars(strip_tags($file_path));

        $stmt->bind_param("si", $file_path, $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getProfilePicture() {
        return $this->profile_picture;
    }
}
?>
