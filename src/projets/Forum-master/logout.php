<?php
session_start();
//On détruit tous ce qui est contenu dans la variables $_SESSION
session_destroy();
//Redirection de l'utilisateur
header("Location:signin.php");
