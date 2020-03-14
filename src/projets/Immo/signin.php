<?php
    require_once ('includes/layout/header.php');
    require_once ('includes/database.php');

    if (!empty($_POST['email']) AND !empty($_POST['password'])) {
        $checkUser = $dbh->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $checkUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $checkUser->execute();
        $user = $checkUser->fetch(PDO::FETCH_ASSOC);

        if ($user AND password_verify($_POST['password'], $user['password'])) {
            $_SESSION['User'] = $user;
            header('Location:index.php');
        }

        $error = 'Mauvais identifiants !';
    }
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <div class="card">
                <div class="card-header">Connexion</div>
                <div class="card-body">
                    <?php
                    if (isset($error)) {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            <?php
                            echo $error;
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="email">Adresse email :</label>
                            <input type="email" name="email" id="email" placeholder="you@domain.com" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe :</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-default">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ('includes/layout/footer.php'); ?>
