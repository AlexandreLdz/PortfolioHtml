<?php
    require_once('includes/layout/header.php');
    require_once('includes/database.php');

    if (empty((int)$_GET['sujet'])) {
        header('Location: index.php');
    }

    $idTopic= $_GET['sujet'];

    if (isset($_GET['delete']) && isset($_SESSION['User']) && !empty((int)$_GET['delete']) && $_GET['delete'] == $idTopic) {
        $commentOwner = $dbh->prepare('SELECT * FROM sujet WHERE id=:id AND u_id=:uId LIMIT 1');
        $commentOwner->bindValue(':id', $_GET['delete'], PDO::PARAM_INT);
        $commentOwner->bindValue(':uId', $_SESSION['User']['id'], PDO::PARAM_INT);
        $commentOwner->execute();
        $checkOwner = $_SESSION['User']['status'] == 'Administrateur' ? true : $commentOwner->fetch();

        if ($checkOwner){
            $removeTopic =$dbh->prepare('DELETE FROM `sujet` WHERE  `id`=:id');
            $removeTopic->bindValue(':id', $_GET['delete'], PDO::PARAM_INT);
            $removeTopic->execute();


            $removeComment = $dbh->prepare('DELETE FROM `commentaire` WHERE t_id = :tId');
            $removeComment->bindValue(':tId', $idTopic, PDO::PARAM_INT);
            $removeComment->execute();

            header('Location: index.php');
        }
    }
//OR (isset($_GET['ban']) && !empty((int)$_GET['ban']) && $_GET['ban'] == $idTopic))

    if (isset($_GET['ban']) && isset($_SESSION['User']) && !empty((int)$_GET['ban'])) {
        $removeComment =$dbh->prepare('UPDATE `utilisateur` SET `banned`='.true.' WHERE  `id`=:uId');
        $removeComment->bindValue(':uId', $_GET['ban'], PDO::PARAM_INT);
        $removeComment->execute();
    }

    if (isset($_GET['commentaire']) && isset($_SESSION['User']) && !empty((int)$_GET['commentaire'])) {
        $commentOwner = $dbh->prepare('SELECT * FROM commentaire WHERE id=:id AND u_id=:uId LIMIT 1');
        $commentOwner->bindValue(':id', $_GET['commentaire'], PDO::PARAM_INT);
        $commentOwner->bindValue(':uId', $_SESSION['User']['id'], PDO::PARAM_INT);
        $commentOwner->execute();
        $checkOwner = $_SESSION['User']['status'] == 'Administrateur' ? true : $commentOwner->fetch();



        if ($checkOwner){
            $removeComment =$dbh->prepare('DELETE FROM `commentaire` WHERE  `id`=:id');
            $removeComment->bindValue(':id', $_GET['commentaire'], PDO::PARAM_INT);
            $removeComment->execute();
        }
    }

    if (isset($_POST['comment']) && isset($_SESSION['User'])) {
        $addComment = $dbh->prepare('INSERT INTO `commentaire` (`contenu`, `ajoute`, `t_id`, `u_id`) VALUES (:content, NOW(), :topicId, :userId);');
        $addComment->bindValue(':content', $_POST['comment'], PDO::PARAM_STR);
        $addComment->bindValue(':topicId', $idTopic, PDO::PARAM_INT);
        $addComment->bindValue(':userId', $_SESSION['User']['id'], PDO::PARAM_INT);
        $addComment->execute();
    }

    $requete = $dbh->prepare("
        SELECT 
            sujet.*, 
            utilisateur.login,
            utilisateur.avatar,
            utilisateur.id as uId,
            utilisateur.banned as banned
        FROM 
            sujet, 
            utilisateur 
        WHERE 
          sujet.id=:sujetId
          AND sujet.u_id = utilisateur.id 
        ");
    $requete->bindValue(':sujetId', $idTopic, PDO::PARAM_INT);
    $requete->execute();
    $response = $requete->fetch();
    if (!$response) {
        header('Location: index.php');
    }
?>

<div class="row col-md-7 m-auto">
    <div class="col-3 border pt-3">
        <img  src="img/upload/utilisateur/<?php echo $response['avatar']; ?>"  class="img-fluid" alt="<?php echo $response['login'] ?>" title="<?php echo $response['login'] ?>">
        <h5 class="text-center"><a href="profil.php?user=<?php echo $response['uId']; ?>"> <?php echo $response['login'] ?></a></h5>
    </div>
    <div class="col-9 border">

        
        <h3><?php echo $response['titre']; ?></h3> 
          
        <p> <?php echo $response['description']; ?></p>
        <?php
            if (isset($_SESSION['User']) && ($response['uId'] == $_SESSION['User']['id'] OR in_array($_SESSION['User']['status'], ['Administrateur', 'Moderateur']))) {
                echo '<a class="btn btn-warning" href="topic.php?sujet='.$idTopic.'&delete='.$response['id'].'">Supprimmer</a>';
            }

            if (isset($_SESSION['User']) && $_SESSION['User']['status'] == 'Administrateur' AND $_SESSION['User']['id'] != $response['uId'] AND !$response['banned']) {
                echo '<a class="btn btn-danger ml-3" href="topic.php?sujet='.$idTopic.'&ban='.$response['uId'].'">Bannir</a>';
            }
        ?>
    </div>
    
<?php
    $requete = $dbh->prepare('
        SELECT 
            commentaire.*, 
            utilisateur.login,
            utilisateur.avatar,
            utilisateur.id as uId,
            utilisateur.banned as banned
        FROM 
            commentaire,
            utilisateur 
        WHERE 
            t_id=:sujetId
            AND commentaire.u_id = utilisateur.id
    ');
    $requete->bindValue(':sujetId', $idTopic, PDO::PARAM_INT);
    $requete->execute();
    $commentaires = $requete->fetchAll();
?>
    <section class="row col-10 m-auto">
        <?php
        foreach ($commentaires as $commentaire) {
        ?>
            <div class="col-3 border pt-3">
                <img  src="img/upload/utilisateur/<?php echo $commentaire['avatar']; ?>"  class="img-fluid" alt="<?php echo $commentaire['login'] ?>" title="<?php echo $commentaire['login'] ?>">
                <h5 class="text-center"><a href="profil.php?user=<?php echo $commentaire['uId']; ?>"> <?php echo $commentaire['login'] ?></a></h5>
            </div>
            <div class="col-9 border">
                <p> <?php echo $commentaire['contenu']; ?></p>
                <span class="muted" >Posté le : <?php echo date('j/m/Y', strtotime($commentaire['ajoute'])) ?></span><br>
                <?php
                    if (isset($_SESSION['User']) && ($commentaire['uId'] == $_SESSION['User']['id'] OR $_SESSION['User']['status'] == 'Administrateur')) {
                        echo '<a class="btn btn-warning" href="topic.php?sujet='.$idTopic.'&commentaire='.$commentaire['id'].'">Supprimmer</a>';
                    }

                    if (isset($_SESSION['User']) && $_SESSION['User']['status'] == 'Administrateur' AND $_SESSION['User']['id'] != $commentaire['uId'] AND !$commentaire['banned']) {
                        echo '<a class="btn btn-danger ml-3" href="topic.php?sujet='.$idTopic.'&ban='.$commentaire['uId'].'">Bannir</a>';
                    }
                ?>
            </div>

        <?php
        }
        ?>
    </section>

    <?php
    if (isset($_SESSION['User'])) {
        ?>

        <form class="mt-5 row col-12" method="post" action="">
            <div class="form-group col-12">
                <textarea class="form-control" id="textarea" name="comment" rows="3"></textarea>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-success">Commenté</button>
            </div>
        </form>

        <?php
    }
    ?>
</div>

<?php require_once('includes/layout/footer.php'); ?>
