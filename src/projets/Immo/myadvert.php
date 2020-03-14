<?php
require_once ('includes/layout/header.php');
require_once ('includes/database.php');

$getMyAdvert = $dbh->prepare('SELECT * FROM adverts WHERE user_id = :id');
$getMyAdvert->bindValue(':id', $_SESSION['User']['id']);
$getMyAdvert->execute();
$myAdverts = $getMyAdvert->fetchAll();
?>

<div class="container">
    <div class="row mt-3 mb-3">
        <h2 class="col-8">Mes annonces : </h2>
        <div class="col-4">
            <a href="editadvert.php" class="btn btn-success">Ajouter une annonce</a>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <?php foreach ($myAdverts as $myAdvert) {
            $adPictureQuery = $dbh->prepare('SELECT * FROM picture WHERE id_ad = :idAd ORDER BY id ASC LIMIT 1');
            $adPictureQuery->bindValue(':idAd', $myAdvert['id'], PDO::PARAM_INT);
            $adPictureQuery->execute();
            $adPicture = $adPictureQuery->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="card col-3 mr-1">
            <img class="card-img-top" src="img/<?php echo $adPicture['name']; ?>" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title"><?php echo $myAdvert['title']; ?></h5>
                <a href="advert.php?id=<?php echo $myAdvert['id']; ?>" class="btn btn-primary">Voir plus</a>
                <a href="editadvert.php?update=<?php echo $myAdvert['id']; ?>" class="btn btn-warning">Modifier</a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php require_once ('includes/layout/footer.php'); ?>
