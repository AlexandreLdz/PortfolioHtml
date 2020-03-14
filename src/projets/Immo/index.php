<?php
    require_once ('includes/layout/header.php');
    require_once ('includes/database.php');

    $actualPage = (isset($_GET['page']) AND !empty((int)$_GET['page'])) ? $_GET['page'] : 0;
    $limit = 1;
    $firstAdvert = $actualPage*$limit;

    $ville = '%';
    $villeGet = '';
    $zipCode = '%';
    $zipCodeGet = '';
    $prixmin = 0;
    $prixminGet = '';
    $prixmax = 10000000000;
    $prixmaxGet = '';
    $typeannonce = '%';
    $typeannonceGet = '';
    $nbpieces = 0;
    $nbpiecesGet = '';
    $areamin = 0;
    $areaminGet = '';
    $areamax = 10000000000;
    $areamaxGet = '';

    if (!empty($_GET['ville'])) {
        $ville = $_GET['ville'];
        $villeGet = $ville;
    }
    if (!empty($_GET['zip_code'])) {
        $zipCode = $_GET['zip_code'];
        $zipCodeGet = $zipCode;
    }
    if (!empty($_GET['prixmin'])) {
        $prixmin =(int)$_GET['prixmin'];
        $prixminGet = $prixmin;
    }
    if (!empty($_GET['prixmax'])) {
        $prixmax =(int)$_GET['prixmax'];
        $prixmaxGet = $prixmax;
    }
    if (!empty($_GET['typeannonce']) AND in_array($_GET['typeannonce'], ['achat','location'])) {
        $typeannonce = $_GET['typeannonce'];
        $typeannonceGet = $typeannonce;
    }
    if (!empty($_GET['nbpieces'])) {
        $nbpieces = (int)$_GET['nbpieces'];
        $nbpiecesGet = $nbpieces;
    }
    if (!empty($_GET['areamin'])) {
        $areamin = (int)$_GET['areamin'];
        $areaminGet = $areamin;
    }
    if (!empty($_GET['areamax'])) {
        $areamax = (int)$_GET['areamax'];
        $areamaxGet = $areamax;
    }

    $url = 'index.php?ville='.$villeGet.'&zip_code='.$zipCodeGet.'&prixmin='.$prixminGet.'&prixmax='.$prixmaxGet.'&typeannonce='.$typeannonceGet.'&nbpieces='.$nbpiecesGet.'&areamin='.$areaminGet.'&areamax='.$areamaxGet.'';

    $advertsQuery = $dbh->prepare('
      SELECT * 
      FROM adverts 
      WHERE
        price >= :prixmin
        AND price <= :prixmax
        AND room >= :nbpieces
        AND area >= :areamin
        AND area <= :areamax
        AND sale_date <= NOW()
        AND advert_type LIKE :typeannonce
        AND ville LIKE :ville
        AND zip_code LIKE :zipCode
        LIMIT :start,:offset
    ');

    $advertsQuery->bindValue(':prixmin', $prixmin, PDO::PARAM_INT);
    $advertsQuery->bindValue(':prixmax', $prixmax, PDO::PARAM_INT);
    $advertsQuery->bindValue(':typeannonce', $typeannonce, PDO::PARAM_STR);
    $advertsQuery->bindValue(':ville', $ville, PDO::PARAM_STR);
    $advertsQuery->bindValue(':zipCode', $zipCode, PDO::PARAM_STR);
    $advertsQuery->bindValue(':nbpieces', $nbpieces, PDO::PARAM_INT);
    $advertsQuery->bindValue(':areamin', $areamin, PDO::PARAM_INT);
    $advertsQuery->bindValue(':areamax', $areamax, PDO::PARAM_INT);
    $advertsQuery->bindValue(':start', $firstAdvert, PDO::PARAM_INT);
    $advertsQuery->bindValue(':offset', $limit, PDO::PARAM_INT);

    $advertsQuery->execute();

    $adverts = $advertsQuery->fetchAll(PDO::FETCH_ASSOC);

    $countAdvertQuery = $dbh->prepare('
        SELECT COUNT(*) as count
          FROM adverts 
          WHERE
            price >= :prixmin
            AND price <= :prixmax
            AND room >= :nbpieces
            AND area >= :areamin
            AND area <= :areamax
            AND sale_date <= NOW()
            AND advert_type LIKE :typeannonce
            
    ');

    $countAdvertQuery->bindValue(':prixmin', $prixmin, PDO::PARAM_INT);
    $countAdvertQuery->bindValue(':prixmax', $prixmax, PDO::PARAM_INT);
    $countAdvertQuery->bindValue(':typeannonce', $typeannonce, PDO::PARAM_STR);
    $countAdvertQuery->bindValue(':nbpieces', $nbpieces, PDO::PARAM_INT);
    $countAdvertQuery->bindValue(':areamin', $areamin, PDO::PARAM_INT);
    $countAdvertQuery->bindValue(':areamax', $areamax, PDO::PARAM_INT);

    $countAdvertQuery->execute();

    $countAdverts = $countAdvertQuery->fetch(PDO::FETCH_ASSOC)['count'];

    $nbPage = ceil((int)$countAdverts/$limit);
?>

<div class="container">
    <h2 class="mt-3 mb-3">Les dernières annonces :</h2>
    <form class="row mb-5" method="get">
        <div class="row col-10">
            <div class="col-4">
                <input name="ville" type="text" class="form-control" placeholder="Ville" value="<?php echo $villeGet; ?>">
            </div>

            <div class="col-4">
                <input name="zip_code" type="text" class="form-control" placeholder="Code postal" value="<?php echo $zipCodeGet; ?>">
            </div>

            <div class="col-2">
                <input name="prixmin" type="text" class="form-control" placeholder="Prix min" value="<?php echo $prixminGet ?>">
            </div>

            <div class="col-2">
                <input name="prixmax" type="text" class="form-control" placeholder="Prix max" value="<?php echo $prixmaxGet ?>">
            </div>
            <div class="col-6">
                <select name="typeannonce" class="custom-select col mt-3">
                    <option disabled
                    <?php
                        if (empty($typeannonceGet)) {
                            echo 'selected';
                        }
                    ?>
                    >Type de recherche</option>
                    <option value="location"
                        <?php
                            if ($typeannonceGet == 'location') {
                                echo 'selected';
                            }
                        ?>
                    >Location</option>
                    <option value="achat"
                        <?php
                        if ($typeannonceGet == 'achat') {
                            echo 'selected';
                        }
                        ?>
                    >Achat</option>
                </select>
            </div>

            <div class="col-2 mt-3">
                <input name="nbpieces" type="text" class="form-control" placeholder="Nb pièces" value="<?php echo $nbpiecesGet ?>">
            </div>

            <div class="col-2 mt-3">
                <input name="areamin" type="text" class="form-control" placeholder="Surface min" value="<?php echo $areaminGet ?>">
            </div>

            <div class="col-2 mt-3">
                <input name="areamax" type="text" class="form-control" placeholder="Surface max" value="<?php echo $areamaxGet ?>">
            </div>
        </div>
        <div class="col-2">
            <button type="submit" class="btn btn-warning col">Rechercher</button>
        </div>

    </form>
    <div class="d-flex justify-content-between">
        <?php
        foreach ($adverts as $advert) {
            $adPictureQuery = $dbh->prepare('SELECT * FROM picture WHERE id_ad = :idAd ORDER BY id ASC LIMIT 1');
            $adPictureQuery->bindValue(':idAd', $advert['id'], PDO::PARAM_INT);
            $adPictureQuery->execute();
            $adPicture = $adPictureQuery->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="card col-3 mr-1 mb-2">
            <img class="card-img-top" src="img/<?php echo $adPicture['name']; ?>" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title"><?php echo $advert['title'] ?></h5>
                <a href="advert.php?id=<?php echo $advert['id'] ?>" class="btn btn-primary">Voir plus</a>
            </div>
        </div>
        <?php } ?>
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination d-flex justify-content-center">
            <li class="page-item <?php echo $title = $actualPage == 0 ? 'disabled' : ''; ?>"><a class="page-link" href="<?php echo $title = $actualPage == 0 ? '#' : $url.'&page='.((int)$actualPage-1); ?>">Previous</a></li>
            <li class="page-item disabled"><a class="page-link" href="#"><?php echo $actualPage+1?></a></li>
            <li class="page-item <?php echo $title = $actualPage+1 < $nbPage ? '' : 'disabled'; ?>"><a class="page-link" href="<?php echo $title = $actualPage+1 < $nbPage ? $url.'&page='.((int)$actualPage+1) : '#'; ?>">Next</a></li>

        </ul>
    </nav>
</div>

<?php require_once ('includes/layout/footer.php'); ?>
