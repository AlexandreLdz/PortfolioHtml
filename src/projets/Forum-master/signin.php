<?php
    require_once('includes/layout/header.php');
    require_once('includes/database.php')
?>
<form action="#" method="post" class="row col-md-3 m-auto border p-3">

<?php

if(isset($_POST['login']))
{
    $login = $_POST['login'];

    $requete = $dbh->prepare("SELECT * FROM utilisateur WHERE login = :login LIMIT 1");
    $requete->bindValue(':login', strtolower($login), PDO::PARAM_STR);
    $requete->execute();
    
    $reponse = $requete->fetch(PDO::FETCH_ASSOC);

   /* if(password_verify($_POST['mdp'], $reponse['password']))
    {
        if (!$reponse['banned']) {
            $_SESSION['User'] = $reponse;
            header("Location:index.php");
        }
    }
    echo '<div class="alert alert-danger text-center col" role="alert">Connection impossible</div>';
    */
}
?>

    <div class="form-group col-12">
        <label for="login">Nom d'utilisateur</label>
        <input name="login" type="text" class="form-control" id="login" placeholder="Votre login">
    </div>
    <div class="form-group col-12">
        <label for="password">Mot de passe</label>
        <input name="mdp" type="password" class="form-control" id="password" placeholder="Votre password">
    </div>
    <input type="submit" class="btn btn-primary ml-3" value="Se connecter">
</form>


<?php require_once('includes/layout/footer.php'); ?>
