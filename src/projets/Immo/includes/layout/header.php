<?php session_start(); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AgenceImmo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/lightbox.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="index.php">AgenceImmo</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Accueil</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <?php
                if (empty($_SESSION['User'])) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="signin.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Inscription</a>
                    </li>
                    <?php
                } else {
            ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mon espace</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php
                    if (in_array($_SESSION['User']['status'], ['admin','vendeur'])) { ?>
                        <a class="dropdown-item" href="myadvert.php">Mes Annonces</a>
                        <a class="dropdown-item" href="editadvert.php">Ajouter une annonce</a>
                        <div class="dropdown-divider"></div>
                    <?php } ?>
                    <a class="dropdown-item" href="messenger.php">Messagerie</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </li>
            <?php
                }
                ?>
        </ul>
    </div>
</nav>