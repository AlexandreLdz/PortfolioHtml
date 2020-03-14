<?php
	require_once('includes/layout/header.php');
	require_once ('includes/database.php');

if(isset($_POST['submit']))
{
    if(!empty($_POST['login']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        if($_POST['password'] == $_POST['confirm_password']) {
			$requete = $dbh->prepare('INSERT INTO utilisateur(login,password,email,banned) VALUES (:login,:password,:email, false)');
	        $requete->bindValue(':login',strtolower($_POST['login']), PDO::PARAM_STR);
	        $requete->bindValue(':email',strtolower($_POST['email']), PDO::PARAM_STR);
	        $requete->bindValue(':password',password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
	        $inscription = $requete->execute();

	        if ($inscription) {
	            header('Location:signin.php');
            }

        } else {
        	$erreur = 'Les mots de passe ne correspondent pas.';
        }
    } else {
		$erreur = 'Les champs sont vides.';
    }
}

$erreur = isset($erreur) ? '<div class="alert alert-danger text-center col" role="alert">'.$erreur.'</div>' : '';
?>



<form method='post' class="row col-md-3 m-auto border p-3">
<?php echo $erreur; ?>
    <div class="form-group col-12">
        <label for="login">Nom d'utilisateur</label>
        <input name='login' type="text" class="form-control" id="login" placeholder="Votre login" required>
    </div>

    <div class="form-group col-12">
        <label for="email">Adresse email</label>
        <input name='email' type="email" class="form-control" id="email" placeholder="Votre email" required>
    </div>

    <div class="form-group col-12">
        <label for="password" class="col">Mot de passe</label>
        <input name='password' type="password" class="form-control" id="password" placeholder="Votre password" required>
    </div>

    <div class="form-group col-12">
        <label for="password">Confirmer votre mot de passe</label>
        <input name='confirm_password' type="password" class="form-control" id="password" placeholder="Répétez votre password" required>
    </div>

    <input type="submit" name="submit" class="btn btn-primary ml-3" value="S'inscrire">

</form>

<?php require_once('includes/layout/footer.php'); ?>
