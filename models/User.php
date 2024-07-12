<?php

class User {
    private $conn;
    private $table_name = "users";

    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct(
        $this -> conn = $db;
    }

    public function register() {

        $query = "INSERT INTO " . $this ->table_name . " SET name=:name, email=:email, password=:password";
        $stmt = $this -> conn -> prepare($query);

        $this -> name = htmlspecialchars(strip_tags($this -> name));
        $this -> email = htmlspecialchars(strip_tags($this -> email));
        $this -> password = htmlspecialchars(strip_tags($this -> password));
        $this -> password = password_hash($this -> password, PASSWORD_BCRYPT);

        $stmt -> bindParam(":name", $this -> name);
        $stmt -> bindParam(":email", $this -> email);
        $stmt -> bindParam(":password", $this -> password);

        if ($stmt -> execute()) {
            return true;
        }

        return false;

    }

    public function login() {
        $query = "SELECT id, name, email, password FROM " . $this -> table_name . " WHERE email = :email";
        $stmt = $this -> conn -> prepare($query);

        $this -> email = htmlspecialchars(strip_tags($this -> email));
        $stmt -> bindParam(":email", $this -> email);

        $stmt -> execute();

        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this -> id = $row['id'];
            $this -> name = $row['name'];
            $this -> email = $row['email'];
            $password_hash = $row['password'];

            if (password_verify($this -> password, $password_hash)) {
                return true;
            }
        }

            return false;
    }
}
?>