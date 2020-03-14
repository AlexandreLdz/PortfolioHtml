<?php
    require_once ('includes/layout/header.php');
    require_once ('includes/database.php');

    if (!empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['status']) AND !empty($_POST['name'])) {
        $email = htmlentities($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $name = htmlentities($_POST['name']);
        $status = htmlentities($_POST['status']);

        $checkUser = $dbh->prepare('SELECT * FROM users WHERE email = :email');
        $checkUser->bindValue(':email', $email, PDO::PARAM_STR);
        $checkUser->execute();

        if (!$checkUser->fetch()) {
            $addUser = $dbh->prepare('INSERT INTO users(`pseudo`,`password`,`email`,`status`) VALUES (:pseudo,:password,:email,:status)');
            $addUser->bindValue(':password', $password, PDO::PARAM_STR);
            $addUser->bindValue(':email', $email, PDO::PARAM_STR);
            $addUser->bindValue(':status', $status, PDO::PARAM_STR);
            $addUser->bindValue(':pseudo', $name, PDO::PARAM_STR);

            if ($addUser->execute()) {
                header('Location:signin.php');
            }

            $error = 'impossible de créer l\'utilisateur';
        }

        $error = !isset($error) ? 'Utilisateur déjà existant !' : $error;
    }
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <div class="card">
                <div class="card-header">Inscription</div>
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
                            <label for="name">Pseudo :</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe :</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <select name="status" class="custom-select mt-3">
                                <option value="client" selected>Client</option>
                                <option value="vendeur">Vendeur</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ('includes/layout/footer.php'); ?>
