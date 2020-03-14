<?php
    require_once ('includes/layout/header.php');
    require_once ('includes/database.php');

    $messagesQuery = $dbh->prepare('
        SELECT
          users.pseudo as pseudo,
          messages.id as id,
          messages.object as object,
          messages.receiver_id as receiver_id,
          messages.sender_id as sender_id
        FROM
          messages,
          users
        WHERE
          receiver_id = :id
          AND sender_id = users.id
    ');
    $messagesQuery->bindValue(':id', $_SESSION['User']['id'], PDO::PARAM_INT);
    $messagesQuery->execute();
    $messages = $messagesQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-5 mb-5">
    <h4>Derniers messages reçus :</h4>
    <?php
    foreach ($messages as $message) {
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <?php echo $message['pseudo']; ?> vous a envoyé un message
                <?php
                echo '<button class="btn btn-primary ml-3" type="button" data-toggle="collapse" data-target="#message-'.$message['id'].'"
                    aria-expanded="false" aria-controls="message-'.$message['id'].'">Voir plus</button>';
                ?>
            </div>
            <?php
            echo '
            <div class="card-body collapse" id="message-'.$message['id'].'">
                <p class="card-text">'.$message['object'].'</p>
            </div>';
            ?>
        </div>
        <?php
    }
    ?>
</div>

<?php require_once ('includes/layout/footer.php'); ?>
