<?php
    require_once ('includes/layout/header.php');
    require_once ('includes/database.php');
    function addPicture($value) {
        global $dbh;

        $updateUserQuery = $dbh->prepare("INSERT INTO picture (`name`, `id_ad`) VALUES (:value, :id)");
        $updateUserQuery->bindValue(':value', $value, PDO::PARAM_STR);
        $updateUserQuery->bindValue(':id', $_GET['update'], PDO::PARAM_INT);
        return $updateUserQuery->execute();

    }

    function saveImage ($file) {
        if (!empty($file['name'])) {
            $pathFile = __DIR__ . '/img/';
            $newFile = uniqid('image') . $_GET['update'] . '.' . pathinfo($file['name'])['extension'];
            move_uploaded_file($file["tmp_name"], $pathFile . $newFile);
            addPicture($newFile);
        }
    }

    if(isset($_GET['update']) AND !empty($_FILES)) {
        foreach ($_FILES as $file) {
            saveImage($file);
        }
    }

    if (!empty($_GET['delete'])) {
        $deleteImage = $dbh->prepare('DELETE FROM picture WHERE id = :id');
        $deleteImage->bindValue(':id', $_GET['delete'], PDO::PARAM_INT);
        $deleteImage->execute();
    }

    if (!empty($_POST['title'])) {
        $ville = htmlentities($_POST['ville']);
        $zipCode = htmlentities($_POST['zip_code']);
        $title = htmlentities($_POST['title']);
        $price = htmlentities($_POST['price']);
        $piece = htmlentities($_POST['piece']);
        $chambres = htmlentities($_POST['chambres']);
        $surface = htmlentities($_POST['surface']);
        $description = htmlentities($_POST['description']);
        $annonce = isset($_POST['annonce']) ? htmlentities($_POST['annonce']) : 'location';

        $editAdvert = $dbh->prepare('
            UPDATE `agenceimmo`.`adverts`
            SET
                `title` = :title,
                `price` = :price,
                `room` = :piece,
                `bedroom` = :chambres,
                `area` = :surface,
                `description` = :description,
                `advert_type` = :annonce,
                `sale_date` = :sale_date,
                `ville` = :ville,
                `zip_code` = :zipCode
            WHERE
              `id` = :id
        ');

        if (!isset($_GET['update'])) {
            $editAdvert = $dbh->prepare('
              INSERT INTO `agenceimmo`.`adverts`
              (`title`, `price`, `room`, `bedroom`, `area`, `description`, `advert_type`, `sale_date`, `user_id`, `zip_code`, `ville`)
              VALUES (:title, :price, :piece, :chambres, :surface, :description, :annonce, :sale_date, :user_id, :zipCode, :ville)
            ');

            $editAdvert->bindValue(':user_id', $_SESSION['User']['id'], PDO::PARAM_INT);
        }
        else {
            $editAdvert->bindValue(':id', $_GET['update'], PDO::PARAM_INT);
        }

        $editAdvert->bindValue(':title', $title, PDO::PARAM_STR);
        $editAdvert->bindValue(':zipCode', $zipCode, PDO::PARAM_STR);
        $editAdvert->bindValue(':ville', $ville, PDO::PARAM_STR);
        $editAdvert->bindValue(':price', $price, PDO::PARAM_INT);
        $editAdvert->bindValue(':piece', $piece, PDO::PARAM_INT);
        $editAdvert->bindValue(':chambres', $chambres, PDO::PARAM_INT);
        $editAdvert->bindValue(':surface', $surface, PDO::PARAM_INT);
        $editAdvert->bindValue(':description', $description, PDO::PARAM_STR);
        $editAdvert->bindValue(':annonce', $annonce, PDO::PARAM_STR);
        $editAdvert->bindValue(':sale_date', $_POST['date'], PDO::PARAM_STR);

        if ($editAdvert->execute() AND !isset($_GET['update'])) {
            $lastInsertId = $dbh->lastInsertId();
            header('Location:editadvert.php?update='.$lastInsertId);
        }
    }

    if (isset($_GET['update']) AND !empty((int)$_GET['update'])) {
        $getAdvert = $dbh->prepare('SELECT * FROM adverts WHERE id = :id LIMIT 1');
        $getAdvert->bindValue(':id', $_GET['update'], PDO::PARAM_INT);
        $getAdvert->execute();
        $advert = $getAdvert->fetch(PDO::FETCH_ASSOC);

        $getPictures = $dbh->prepare('SELECT * FROM picture WHERE id_ad = :id LIMIT 4');
        $getPictures->bindValue(':id', $_GET['update'], PDO::PARAM_INT);
        $getPictures->execute();
        $pictures = $getPictures->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>

<div class="container mt-5">
    <form method="post">
        <div class="row">
            <h4 class="col-9">
                Information de l'annonce
            </h4>
            <div class="col-3 text-right">
                <button type="submit" class="btn btn-info">Sauvegarder l'annonce</button>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="Titre">Titre :</label>
                <input name="title" type="text" class="form-control" id="Titre" placeholder="Titre" value="<?php echo $title = !empty($advert) ? $advert['title'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label for="Titre">Ville :</label>
                <input name="ville" type="text" class="form-control" id="Titre" placeholder="Ville" value="<?php echo $title = !empty($advert) ? $advert['ville'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label for="Titre">Code postal :</label>
                <input name="zip_code" type="number" class="form-control" id="Titre" placeholder="Code postal" value="<?php echo $title = !empty($advert) ? $advert['zip_code'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label for="Prix">Prix :</label>
                <input name="price" type="number" class="form-control" id="Prix" placeholder="Prix" value="<?php echo $title = !empty($advert) ? $advert['price'] : ''; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="piece">Pièces :</label>
                <input name="piece" type="number" class="form-control" id="piece" placeholder="Pièces" value="<?php echo $title = !empty($advert) ? $advert['room'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="chambres">Chambres :</label>
                <input name="chambres" type="number" class="form-control" id="chambres" placeholder="Chambres" value="<?php echo $title = !empty($advert) ? $advert['bedroom'] : ''; ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="surface">Surface :</label>
                <input name="surface" type="number" class="form-control" id="surface" placeholder="Surface" value="<?php echo $title = !empty($advert) ? $advert['area'] : ''; ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea name="description" class="form-control" id="description" rows="3" required><?php echo $title = !empty($advert) ? $advert['description'] : ''; ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="type">Type :</label>
                <select name="annonce" id="Type" class="form-control"  required>
                    <option disabled
                    <?php echo $title = empty($advert) ? 'selected' : ''; ?>
                    value="location">Type d'annonce</option>
                    <option value="location"
                        <?php echo $title = (!empty($advert) AND $advert['advert_type'] == 'location') ? 'selected' : ''; ?>
                    >Location</option>
                    <option value="achat"
                        <?php echo $title = (!empty($advert) AND $advert['advert_type'] == 'achat') ? 'selected' : ''; ?>
                    >Achat</option>
                </select>
            </div>
            <div class="form-group ml-2">
                <label for="date">Date de mise en vente :</label>
                <input name="date" type="date" id="date" class="form-control" value="<?php echo $title = !empty($advert) ? $advert['sale_date'] : ''; ?>" required>
            </div>
        </div>
    </form>
    <?php
    if (isset($_GET['update'])) {
        ?>
        <hr>
        <form method="post" class="mb-5" enctype="multipart/form-data">
            <div class="row mb-3">
                <h4 class="col-9">
                    Images de l'annonce :
                </h4>
                <div class="col-3 text-right">
                    <button class="btn btn-info" type="submit">Sauvegarder les images</button>
                </div>
            </div>
            <div class="row">
                <?php
                foreach ($pictures as $picture) {
                    echo '
                    <div class="col-3">
                        <img src="img/' . $picture['name'] . '" class="img-thumbnail img-fluid">
                        <a href="editadvert.php?update='.$_GET['update'].'&delete='.$picture['id'].'" class="btn-danger btn col mt-2">Supprimer</a>
                    </div>';
                }
                for ($i = 1; $i <= (4 - count($pictures)); $i++) {
                    echo '
                    <div class="col-3">
                        <img src="img/none.png" class="img-thumbnail img-fluid">
                        <input name="image'.$i.'" class="btn-success btn col mt-2" type="file">
                    </div>';
                }
                ?>
            </div>
        </form>
        <?php
    }
        ?>
</div>

<?php require_once ('includes/layout/footer.php'); ?>
