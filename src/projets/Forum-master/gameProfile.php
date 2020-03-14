<?php
    require_once('includes/layout/header.php');
    require_once ('includes/database.php');

    if (!isset($_GET['jeu']) OR empty((int)$_GET['jeu'])) {
        header('Location: index.php');
    }

    //Si la clé `page` de `$_GET` n'existe on lui attribue un sinon la valeur
    $actualPage = !isset($_GET['page']) && empty($_GET['page']) ? 1 : $_GET['page'];

    if (isset($_POST['title']) && $_SESSION['User']) {
        $addTopic = $dbh->prepare('INSERT INTO `sujet` (`titre`, `description`, `cat_id`, `u_id`) VALUES (:titre, :description, :catId, :uId)');
        $addTopic->bindValue(':titre', $_POST['title'], PDO::PARAM_STR);
        $addTopic->bindValue(':description', $_POST['content'], PDO::PARAM_STR);
        $addTopic->bindValue(':catId', $_GET['jeu'], PDO::PARAM_STR);
        $addTopic->bindValue(':uId', $_SESSION['User']['id'], PDO::PARAM_STR);
        $addTopic->execute();
    }



    //On récupère le nombre de sujet
    $nombreTopicsQuery = $dbh->prepare('SELECT count(*)as count FROM sujet WHERE cat_id=:id');
    $nombreTopicsQuery->bindValue(':id', $_GET['jeu'], PDO::PARAM_INT);
    $nombreTopicsQuery->execute();
    $nombreTopics = (int)$nombreTopicsQuery->fetch()['count'];

    //On arrondi à l'entier superieur la division
    $nombrePages = ceil($nombreTopics/5);

    //Si le numéro de page est superieur au nombre page maximum il est redirigé
    if ($nombrePages < $actualPage && !0 == $nombrePages) {
        header('Location: index.php');
    }
    //Si la page actuel est égal à un on ajoute 'disabled'
    $disablePreviousPage = 1 == $actualPage ? "disabled" : "";

    //Si n'est pas égal à 'disabled' on lui attribu le numéro de la page - 1
    $previousPage = "disabled" == $disablePreviousPage ? "#" : $actualPage - 1;

    //Si la page actuel +1 est superieur au nombre de page maximum on lui set 'disabled'
    $disableNextPage = $actualPage+1 > $nombrePages ? "disabled" : "";
    //Si !'disabled' on set la page actuel + 1
    $nextPage = "disabled" == $disableNextPage ? "#" : $actualPage + 1;

    //Url à utiliser pour la pagination
    $urlPage = "gameProfile.php?game=".$_GET['jeu']."&page=";

    //Si !'disabled' on set le lien vers la page précédente
    $previousPagination = "disabled" == $disablePreviousPage ? "" : "<li class=\"page-item\"><a class=\"page-link\" href=\"".$urlPage.$previousPage."\">".$previousPage."</a></li>";
    //Si !'disabled' on set le lien vers la page suivante
    $nextPagination = "disabled" == $disableNextPage ? "" : "<li class=\"page-item\"><a class=\"page-link\" href=\"".$urlPage.$nextPage."\">".$nextPage."</a></li>";

    //Affiche tout ce qui correspond à l'id récupéré dans l'url
    $gameQuery = $dbh->prepare('SELECT * FROM jeu WHERE id=:id');
    $gameQuery->bindValue(':id', (int)$_GET['jeu'], PDO::PARAM_INT);
    $gameQuery->execute();
    $game = $gameQuery->fetch();

    if (!$game) {
        header('Location: index.php');
    }
?>

<!-- About game -->
<section class="row col-md-7 m-auto">
    <h3 class="col-sm-12 bg-dark text-white p-1"><?php echo $game['nom']; ?></h3>
    <div class="col-md-6 p-0 mb-3">
        <img src="img/upload/jeu/<?php echo $game['image']; ?>" alt="<?php echo $game['nom']; ?>" title="<?php echo $game['nom']; ?>" class="img-fluid">
    </div>
    <ul class="list-group col-md-6">
        <li class="list-group-item">Date de sortie : <?php echo date('d/m/Y' ,strtotime($game['date_de_sortie'])); ?></li>
        <li class="list-group-item">Plateforme : <?php echo $game['plateforme']; ?></li>
        <li class="list-group-item">Développeur : <?php echo $game['developpeur']; ?></li>
    </ul>
    <h5 class="mt-3">Description :</h5>
    <p class="text-justify"><?php echo $game['description']; ?></p>

    <?php
        if (isset($_SESSION['User'])) {
    ?>
            <form class="mt-5 row col-12" method="post" action="">
                <div class="form-group col-12">
                    <input type="text" class="form-control" name="title" placeholder="Titre du sujet..." maxlength="200" required>
                </div>
                <div class="form-group col-12">
                    <textarea class="form-control" id="textarea" name="content" rows="3" placeholder="Contenu du sujet..." required></textarea>
                </div>
                <div class="col">
                    <input type="submit" class="btn btn-success" value="Ajouter un sujet">
                </div>
            </form>
    <?php
        }
    ?>
</section>

<!-- Game topics -->
<section class="row col-md-7 m-auto">
    <h5 class="mt-5 col-12">Derniers sujets :</h5>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Sujet</th>
                <th scope="col">Jeu</th>
                <th scope="col">Auteur</th>
            </tr>
        </thead>
        <tbody>
        <?php
            //Récupère 5 sujet correspondant à la page actuel
            $postsQuery = $dbh->prepare('
                SELECT 
                    sujet.id as sujet_id, 
                    sujet.titre as sujet_titre, 
                    jeu.id as jeu_id, 
                    jeu.nom as jeu_titre, 
                    utilisateur.id as utilisateur_id,
                    utilisateur.login as utilisateur_login 
                FROM 
                    sujet,
                    jeu,
                    utilisateur 
                WHERE 
                    sujet.cat_id=:id
                    AND sujet.u_id = utilisateur.id 
                    AND sujet.cat_id = jeu.id
                ORDER BY sujet.id DESC
                LIMIT :start,5
            ');
            $postsQuery->bindValue(':id', $_GET['jeu'], PDO::PARAM_INT);
            $postsQuery->bindValue(':start', ($actualPage-1)*5, PDO::PARAM_INT);
            $postsQuery->execute();
            $posts = $postsQuery->fetchAll();

            foreach ($posts as $post) {
                echo "
                    <tr>
                        <th scope=\"row\"><a href=\"topic.php?sujet=".$post['sujet_id']."\">".$post['sujet_titre']."</a></th>
                        <td><a href=\"gameProfile.php?jeu=".$post['jeu_id']."\" class=\"text-dark author\">".$post['jeu_titre']."</a></td>
                        <td><a href=\"profil.php?user=".$post['utilisateur_id']."\" class=\"text-dark\">".$post['utilisateur_login']."</a></td>
                    </tr>
                ";
            }
        ?>
        </tbody>
    </table>
<?php
    if (!0 == $nombrePages) {
?>
    <nav aria-label="Page navigation example" class="m-auto pt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $disablePreviousPage; ?>">
                <a class="page-link" href="<?php echo $urlPage.$previousPage ?>" tabindex="-1">Précédent</a>
            </li>
            <?php echo $previousPagination; ?>
            <li class="page-item active"><a class="page-link" href="#"><?php echo $actualPage ?></a></li>
            <?php echo $nextPagination;?>
            <li class="page-item <?php echo $disableNextPage; ?>">
                <a class="page-link" href="<?php echo $urlPage.$nextPage ?>">Suivant</a>
            </li>
        </ul>
    </nav>

<?php } ?>
</section>
<?php require_once('includes/layout/footer.php'); ?>
