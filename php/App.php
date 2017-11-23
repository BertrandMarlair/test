<?php
    require 'auth.php';
    class App{
        static $db = null;

        static function getDatabase(){
            if(!self::$db) {
                self::$db = new Database('domaine', 'motdepasse', 'user');
            }
                return self::$db;
        }

        static function getAuth(){
            return new auth(session::getInstance(), ['restriction_msg' => "Vous n'avez pas acces a cette page"]);
        }

        static function redirect($page){
            header("Location: $page");
            exit();
        }
    }	