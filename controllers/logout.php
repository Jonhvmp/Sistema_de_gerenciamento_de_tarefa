<?php
// Author: Jonh Alex Paz de Lima
// All rights reserved
session_start();
session_destroy();
header("Location: ../views/login.php");
exit();
?>
