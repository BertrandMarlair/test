<?php

require 'inc/bootstrap.php';
$db = App::getDatabase();
require 'inc/Form.php';
$auth = App::getAuth();
require 'inc/header.php';
$req = $db->query("SELECT * FROM ckeditor ORDER BY id DESC");

if(isset($_SESSION['auth'])){
    $a = $_SESSION['auth']->role_id;
}else{
    $a = 0;
}

?>
<hr>
<h1>MultimÃ©dia</h1>

<?php if($a >=2){ ?>
    <div class="container">
        <p>
            <a class="btn btn-default btn-lg" href="ajouter-page.php">Ajouter un article &raquo;</a>
        </p>
        <br/>
    </div>
    <?php
} ?>

        <?php

        while($content = $req->fetch(PDO::FETCH_ASSOC)){
            ?>
            <div style="border-style: solid;border-width: 1px;border-color: #DADADA; background-color: #F7F5F5; padding: 10px; margin-bottom: 20px;">
                <p><?php echo $content['content']?></p>
                <?php if($a >=2){ ?>
                <p><a href="ajouter-page.php?id=<?= $content['id']; ?>">Edit</a>
                <p><a href="suppr_multi.php?id=<?= $content['id']; ?>">Supprimer</a>
                    <?php } ?>
                <hr/>
            </div>

        <?php } ?>


</body>
</html>


<?php
require 'inc/footer.php';
unset($_SESSION['inputs']);
unset($_SESSION['success']);
unset($_SESSION['errors']);
?>