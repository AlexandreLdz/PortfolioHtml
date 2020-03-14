<?php
    require_once('includes/layout/header.php');
    require_once ('includes/database.php');
    require_once ('includes/library/resizeImage.php');

//    Seul un utilisateur connecté peut se rendre sur cette page
    if(empty($_SESSION['User'])){
        header('Location: signin.php');die;
    }

//    Fonction évitant une répétition de la requête permettant de modifier un fichier en particulier
    function updateUserField($field, $value, $id) {
        global $dbh;

        $updateUserQuery = $dbh->prepare("UPDATE utilisateur SET $field=:value WHERE id=:id");
        $updateUserQuery->bindValue(':value', $value, PDO::PARAM_STR);
        $updateUserQuery->bindValue(':id', $id, PDO::PARAM_INT);
        return $updateUserQuery->execute();

    }

    if($_POST) {
        if(!empty($_POST['login']) && $_POST['login'] != $_SESSION['User']['login']){
            updateUserField('login', $_POST['login'], $_SESSION['User']['id']);
            $_SESSION['User']['login'] = $_POST['login'];
        }

        if(!empty($_POST['email']) && $_POST['email'] != $_SESSION['User']['email']){
            updateUserField('email', $_POST['email'], $_SESSION['User']['id']);
            $_SESSION['User']['email'] = $_POST['email'];
        }

        if(!empty($_POST['password']) && !password_verify($_POST['password'],$_SESSION['User']['password'])){
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            updateUserField('password', $password, $_SESSION['User']['id']);
            $_SESSION['User']['password'] = $password;
        }

        if(!empty($_POST['description']) && $_POST['description'] != $_SESSION['User']['description']){
            updateUserField('description', $_POST['description'], $_SESSION['User']['id']);
            $_SESSION['User']['description'] = $_POST['description'];
        }

        if(!empty($_FILES) && $_FILES['avatar']['name']){
//            Chemin vers le dossier où sera enregistré l'image
            $pathFile = __DIR__.'/img/upload/utilisateur/';
            $newFile = 'utilisateur-'.$_SESSION['User']['id'].'.'.pathinfo($_FILES['avatar']['name'])['extension'];
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $pathFile.$newFile);
            updateUserField('avatar', $newFile, $_SESSION['User']['id']);
            $_SESSION['User']['avatar'] = $newFile;
//            On redéfinis la taille de l'image pour éviter que les images rendent encore plus moche le rendu
            $resizeObj = new resize($pathFile.$newFile);
            $resizeObj -> resizeImage(500, 500, 'exact');
            $resizeObj -> saveImage($pathFile.$newFile, 100);
        }
    }

?>

<div class="row col-md-7 m-auto">
    <div class="p-2">
        <form class="row col mb-5" method="post">
            <h3 class="col pb-3 mb-4 border border-top-0 border-right-0 border-left-0">Compte</h3>
            <div class="form-group col-md-12 col-sm-12 row">
                <label for="login" class="col-md-3 col-sm-12">Nom d'utilisateur </label>
                <input type="text" name="login" class="form-control  col-md-4 col-sm-12" id="login" placeholder="Login" value="<?php echo $_SESSION['User']['login']; ?>">
            </div>
            <div class="form-group col-md-12 col-sm-12 row">
                <label for="email" class="col-md-3 col-sm-12">Adresse email </label>
                <input type="email" name="email" class="form-control  col-md-4 col-sm-12" id="email" placeholder="Email" value="<?php echo $_SESSION['User']['email']; ?>">
            </div>
            <div class="form-group col-md-12 col-sm-12 row">
                <label for="password" class="col-md-3 col-sm-12">Mot de passe </label>
                <input type="password" name="password" class="form-control col-md-4 col-sm-12" id="password" placeholder="Password" value="">
            </div>
            <button type="submit" class="btn btn-primary ml-2">Mettre à jour</button>
        </form>

        <form class="row col" method="post" enctype="multipart/form-data">
            <h3 class="col pb-3 mb-4 border border-top-0 border-right-0 border-left-0">Profil</h3>
            <div class="form-group col-md-12 col-sm-12 row">
                <label for="description" class="col-md-3 col-sm-12">Description </label>
                <textarea id="description" class="form-control col-md-9 col-sm-12" name="description"><?php echo $_SESSION['User']['description']; ?></textarea>
            </div>
            <div class="col-md-2">
                <img src="img/upload/utilisateur/<?php echo $_SESSION['User']['avatar'] ?>" class="img-fluid img-thumbnail" alt="<?php echo $_SESSION['User']['login'] ?>" title="<?php echo $_SESSION['User']['login'] ?>">
            </div>
            <div class="form-group col-md-12 col-sm-12 row">
                <label for="file" class="col-md-3 col-sm-12">Image de profil </label>
                <input type="file" class="form-control-file" aria-describedby="profilImg" name="avatar" id="file"><br>
                <small id="profilImg" class="form-text col-12 text-muted">Attention, par soucis de format, l'image est automatique transformé en carré.</small>

            </div>
            <button type="submit" class="btn btn-primary ml-2">Mettre à jour</button>
        </form>
    </div>
</div>

<?php require_once('includes/layout/footer.php'); ?>
