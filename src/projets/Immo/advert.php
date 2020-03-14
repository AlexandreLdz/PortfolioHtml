<?php
require_once ('includes/layout/header.php');
require_once ('includes/database.php');

if (empty($_GET['id']) AND empty($_GET['delete'])) {
    header('Location:index.php');die;
}

if (!empty($_GET['delete'])) {
    $deleteAdvert = $dbh->prepare('DELETE FROM adverts WHERE id = :id');
    $deleteAdvert->bindValue(':id', $_GET['delete'], PDO::PARAM_INT);
    if ($deleteAdvert->execute()) {
        header('Location:index.php');die;
    }
}

$getadvert = $dbh->prepare('SELECT * FROM adverts WHERE id = :id');
$getadvert->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$getadvert->execute();
$advert = $getadvert->fetch(PDO::FETCH_ASSOC);

$PicturesQuery = $dbh->prepare('SELECT * FROM picture WHERE id_ad = :idAd ORDER BY id ASC');
$PicturesQuery->bindValue(':idAd', $_GET['id'], PDO::PARAM_INT);
$PicturesQuery->execute();
$Pictures = $PicturesQuery->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_POST['contact'])) {
    $newMessage = $dbh->prepare('INSERT INTO messages (`receiver_id`, `sender_id`, `object`) VALUES (:receiver_id, :sender_id, :object)');
    $newMessage->bindValue(':receiver_id', $advert['user_id'], PDO::PARAM_INT);
    $newMessage->bindValue(':sender_id', $_SESSION['User']['id'], PDO::PARAM_INT);
    $newMessage->bindValue(':object', $_POST['contact'], PDO::PARAM_STR);
    $newMessage->execute();
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-8">
            <div class="row">
                <h4 class="col-10"><?php echo $advert['title'] ?></h4>
                <h5 class="col-2 text-primary"><?php echo $advert['price'] ?> €</h5>
            </div>
            <p class="text-muted">Ajouté le <?php echo date('d/m/o',strtotime($advert['sale_date'])); ?></p>
            <div id="slider" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php
                        for ($i = 1; $i <= count($Pictures); $i++) {
                            echo '<li data-target="#slider" data-slide-to="'.($i-1).'"';
                            echo $title = $i == 1 ? ' class="active"' : '';
                            echo "></li>";
                        }
                    ?>
                </ol>
                <div class="carousel-inner">
                    <?php
                        for ($i = 1; $i <= count($Pictures); $i++) {
                            echo '<a href="img/'.$Pictures[$i-1]['name'].'" data-lightbox="roadtrip" class="carousel-item';
                            echo $title = $i == 1 ? ' active' : '';
                            echo '"><img class="d-block w-100" src="img/'.$Pictures[$i-1]['name'].'"></a>';
                        }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#slider" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <table class="table table-dark mt-3">
                <thead>
                <tr>
                    <th class="text-center" scope="col">Type d'annonce</th>
                    <th class="text-center" scope="col">Pièces</th>
                    <th class="text-center" scope="col">Chambres</th>
                    <th class="text-center" scope="col">Surface</th>
                    
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center"><?php echo $advert['advert_type'] ?></td>
                    <td class="text-center"><?php echo $advert['room'] ?></td>
                    <td class="text-center"><?php echo $advert['bedroom'] ?></td>
                    <td class="text-center"><?php echo $advert['area'] ?> m²</td>
                </tr>
                </tbody>
            </table>
            <h4 class="mt-3 mb-3"><?php echo $advert['ville'].' ('. $advert['zip_code']?>)</h4>
            <p><?php echo $advert['description'] ?> </p>
        </div>
        <div class="col-4">
<?php if (!empty($_SESSION) AND ($_SESSION['User']['status'] == 'admin' OR $advert['user_id'] == $_SESSION['User']['id'])) { ?>
            <div class="card">
                <h5 class="card-header">Editer l'annonce</h5>
                <div class="card-body d-flex justify-content-around">
                    <div class="col-6"><a href="advert.php?delete=<?php echo $advert['id']; ?>" class="btn btn-danger">Supprimer</a></div>
                    <div class="col-6"><a href="editadvert.php?update=<?php echo $advert['id']; ?>" class="btn btn-warning">Modifier</a></div>
                </div>
            </div>
    <?php } else { ?>
            <div class="card mb-3">
                <h5 class="card-header">Contacter le propriétaire</h5>
                <?php if (!empty($_SESSION['User'])) { ?>
                <form class="card-body" method="post">
                    <div class="form-group">
                        <label for="Message">Votre message :</label>
                        <textarea name="contact" class="form-control" id="Message" rows="3" placeholder="Pensez à indiquer si vous êtes un étudiant, un couple en CDD / CDI, etc., vos revenus et si vous avez un garant."></textarea>
                    </div>
                    <button type="submit" class="btn btn-default">Envoyer</button>
                </form>
        <?php } else { ?>
                <div class="row mt-3 mb-3">
                    <div class="col text-center"><a href="signin.php" class="btn btn-info">Se connecter</a></div>
                </div>
        <?php } ?>
            </div>
    <?php } ?>
        </div>
    </div>
</div>

<?php require_once ('includes/layout/footer.php'); ?>