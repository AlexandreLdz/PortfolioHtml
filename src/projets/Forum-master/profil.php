<?php
    require_once('includes/layout/header.php');
    require_once ('includes/database.php');

    if (empty($_GET['user']) && empty($_SESSION['User'])) {
        header('Location: index.php');die;
    }

    if (!empty($_GET['user']))
    {
        $userQuery = $dbh->prepare('SELECT id,inscription,status,login,avatar, description FROM utilisateur where id = :id');
        $userQuery->bindValue(':id', $_GET['user'], PDO::PARAM_INT);
        $userQuery->execute();
        $sqlUser = $userQuery->fetch();

        if (!$sqlUser) {
            header('Location: index.php');die;
        }
    }

    $user = !isset($sqlUser) ? $_SESSION['User'] : $sqlUser;

    //Récupération du nombre de commentaire de l'utilisateur
    $nbPostsQuery = $dbh->prepare('SELECT count(*) as count FROM commentaire where u_id = :id');
    $nbPostsQuery->bindValue(':id', $user['id'], PDO::PARAM_INT);
    $nbPostsQuery->execute();
    $nbPosts = $nbPostsQuery->fetch();

    //Récupération des 5 derniers messages posté par l'utilisateur
    $postsQuery = $dbh->prepare('SELECT contenu,t_id FROM commentaire where u_id = :id ORDER BY id DESC LIMIT 5');
    $postsQuery->bindValue(':id', $user['id'], PDO::PARAM_INT);
    $postsQuery->execute();
    $posts = $postsQuery->fetchAll();

?>

<div class="row col-md-8 m-auto">

    <!-- Left side -->
    <div class="col-md-3 col-sm-12 mb-5">
        <div class="col-md-9 m-auto">
            <img src="img/upload/utilisateur/<?php echo $user['avatar'] ?>" class="img-fluid rounded-circle img-thumbnail" alt="<?php echo $user['login'] ?>" title="<?php echo $user['login'] ?>">
        </div>
        <h3 class="text-center mt-3"><?php echo $user['login'] ?></h3>
        <ul class="list-group mt-5">
            <li class="list-group-item">Inscrit depuis : <?php echo date('d/m/y', strtotime($user['inscription'])); ?></li>
            <li class="list-group-item">Messages : <?php echo $nbPosts['count']; ?></li>
            <li class="list-group-item">Status : <?php echo $user['status']; ?></li>
        </ul>
    </div>

    <!-- Right side-->
    <div class="col-md-9 col-sm-12 tab-content" id="nav-tabContent">

        <!-- Tab list -->
        <ul class="nav nav-tabs" id="list-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link text-dark font-weight-bold active" href="#list-about" id="list-profile-list" data-toggle="list" role="tab" aria-controls="home">À propos</a>
            </li>
        </ul>

        <!-- About -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane pt-5 fade show active" id="list-about" role="tabpanel" aria-labelledby="list-profile-list">
                <div class="col row">
                    <div class="col-12">
                        <h5 class="mb-1">Description :</h5>
                        <p class="text-justify"><?php echo $user['description'] ?></p>
                    </div>
                    <div class="col-12 row border border-bottom-0 border-right-0 border-left-0 pt-3">
                        <h3 class="mb-3 col-12">Derniers commentaires :</h3>
                        <ul class="list-unstyled ml-3">
                        <?php
                            foreach ($posts as $post) {
                                echo '
                                <li class="row mb-3">
                                    <div class="d-none d-md-block col-md-2 col-sm-12 ">
                                        <img class="img-fluid mr-3 rounded-circle" src="img/upload/utilisateur/'.$user['avatar'].'" alt="'.$user['login'].'" title="'.$user['login'].'">
                                    </div>
                                    <div class="col-md-10 col-sm-12">
                                        <a href="topic.php?sujet='.$post['t_id'].'"><p class="text-justify">'.$post['contenu'].'</p></a>
                                    </div>   
                                </li> 
                                ';
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/layout/footer.php'); ?>

