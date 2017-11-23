<?php
Class Affiche{
    public $toto;

    function __construct($toto){
        $this->boucle($toto);
    }

    public function boucle($porte){
        for($i = 0; $i < count($porte); $i++){
            echo $porte[$i];
            echo '<br/>';
        }
    }
}
?>