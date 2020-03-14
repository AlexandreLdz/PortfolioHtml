<?php
    require_once('includes/layout/header.php');
    require_once('includes/database.php');
?>

<div class="row col-md-7 m-auto">
    <div class="card col-12 mb-5 p-0">
        <h4 class="card-header">Derniers sujet</h4>
        <div class="card-body col">
            <table class="table">
                <tbody>
                <?php
                //Récupère les derniers sujet et leurs nombres de messages
                $gamesQuery = $dbh->query('
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
                      sujet.u_id = utilisateur.id 
                      AND sujet.cat_id = jeu.id
                    ORDER BY sujet.id DESC
                    LIMIT 5
                ');
                $games = $gamesQuery->fetchAll();

                foreach ($games as $game) {
                    echo '
                        <tr>
                            <td><a href="topic.php?sujet='.$game['sujet_id'].'">'.ucfirst($game['sujet_titre']).'</a></td>
                            <td><a href="gameProfile.php?jeu='.$game['jeu_id'].'" class="text-dark author">'.$game['jeu_titre'].'</a></td>
                            <td><a href="profil.php?user='.$game['utilisateur_id'].'" class="text-dark author">'.ucfirst($game['utilisateur_login']).'</a></td>
                        </tr>                  
                            ';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card col-12 p-0">
        <h4 class="card-header">Sujets les plus commentés</h4>
        <div class="card-body col">
            <table class="table">
                <tbody>
                <?php
                    //Récupère les sujets e fonctions de leurs messages dans l'ordre décroissant
                    $gamesQuery = $dbh->query('
                        SELECT 
                            sujet.titre as sujet_titre, 
                            jeu.nom as jeu_titre, 
                            commentaire.count as nb_commentaire,
                            sujet.id as sujet_id,
                            jeu.id as jeu_id     
                        FROM
                            sujet,
                            jeu,
                            (SELECT 
                                count(*) as count, 
                                t_id
                            FROM commentaire 
                            GROUP BY t_id
                            ORDER BY count DESC
                            ) as commentaire
                        WHERE
                            commentaire.t_id = sujet.id
                            AND sujet.cat_id = jeu.id
                        LIMIT 5
                        ');
                    $games = $gamesQuery->fetchAll();
                    
                    foreach ($games as $game) {
                        echo '
                        <tr>
                            <td><a href="topic.php?sujet='.$game['sujet_id'].'">'.$game['sujet_titre'].'</a></td>
                            <td><a href="gameProfile.php?jeu='.$game['jeu_id'].'" class="text-dark author">'.$game['jeu_titre'].'</a></td>
                            <td>'.$game['nb_commentaire'].' Messages</td>
                        </tr>                  
                            ';
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php require_once('includes/layout/footer.php'); ?>
