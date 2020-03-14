<?php
    require_once('includes/layout/header.php');
    require_once ('includes/database.php');
?>

<!--<div class="row col">-->
<!--    <a href="#" class="col-md-1 col-sm-12 mr-1 btn btn-primary">Tous</a>-->
<!--    <a href="#" class="col-md-1 col-sm-12 mr-1 btn btn-secondary">MOBA</a>-->
<!--    <a href="#" class="col-md-1 col-sm-12 mr-1 btn btn-success">FPS</a>-->
<!--    <a href="#" class="col-md-1 col-sm-12 mr-1 btn btn-danger">MMORPG</a>-->
<!--    <a href="#" class="col-md-1 col-sm-12 mr-1 btn btn-warning">Sport</a>-->
<!--</div>-->

<div class="d-flex flex-row raw col flex-wrap">
    <?php
        //On récupère tous les jeux par ordre croissant
        $categoriesQuery = $dbh->query("SELECT * FROM jeu ORDER BY nom ASC");
        $categories = $categoriesQuery->fetchAll();
        //On fait une boucle pour que chaque jeu s'affiche
        foreach ($categories as $category) {
            //On affiche les informations du jeu nécessaire
            echo"
                <a href=\"gameProfile.php?jeu=".$category['id']."\" class=\"col-md-2 mt-3\">
                    <img src=\"img/upload/jeu/".$category['image']."\" alt=\"".$category['nom']."\" title=\"".$category['nom']."\" class=\"img-thumbnail img-fluid\">
                </a>
            ";
        }
    ?>
</div>

<?php require_once('includes/layout/footer.php'); ?>
