Class:
    - additionner
    - soustraction
    - division
    - mutiplication

Declarer 4 variables -> partern : 
    ('type', les valeurs dans un tableau)

    ex: 
    ('soustraction', [1,2,3,4])














<?php
require 'class.php';
require 'class2.php';
$num1 = [1, 2];
$num2 = [5, 6];
$chiffre1 = 10;
$chiffre2 = 20;

$test = new Affiche($num1);
$test2 = new Numero('bonjour', $num2);
$test2->paul($chiffre1, $chiffre2);

?>