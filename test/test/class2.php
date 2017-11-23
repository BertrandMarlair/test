












<?php
Class Numero{
    public $boite1;
    public $boite2;

    function __construct($boite1, $boite2){
        $this->boulanger($boite1, $boite2);
    }

    public function boulanger($gateau1,$gateau2){
        var_dump($gateau1);
        echo '<br/>';
        var_dump($gateau2);
    }

    public function paul($num1, $num2){
        echo $num1 + $num2;
    }
}
?>