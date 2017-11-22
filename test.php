<?php
try
{
	// On se connecte à MySQL
	$bdd = new PDO('pgsql:host=ec2-54-75-233-162.eu-west-1.compute.amazonaws.com;dbname=ddl32bedp88msc;', 'topizpowfzcgrh', '35dafbbd85345da46254cb6fd4d53d9fd94f73e06851bdf4b98bec08a649ede0');
}
catch(Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}