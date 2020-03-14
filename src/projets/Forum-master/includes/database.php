<?php

require_once ('auth.php');

try {
    //Connection à la BDD
    $dbh = new PDO('mysql:host='.HOST.';charset=utf8;dbname='.DATABASE, USER, PASSWORD);
} catch (PDOException $e) {
    //Retourne l'erreur si elle est existante.
    print "Erreur !: " . $e->getMessage() . "<br/>";
    //On arrête le chargement de la page
    die();
}