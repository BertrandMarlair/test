<?php
require 'class.php';
$tableau = ['test1', 'test2', 'test3'];
$test = new Affiche($tableau);


echo '

    index.php le même qu avant,
    toto.php -> envoie a la class un table 
                avec deux valeurs numériques.
    class -> pareil;

    on appel la même class en 
    lui envoyant des params diffs
    d un endroit diff aussi

';
?>