<?php 
    function connection(){
        $base= mysqli_connect('localhost', 'root', '', 'emprunt');
        //$base= mysqli_connect('localhost' ,'ETU004153' ,'zyBDdP65Z', 'db_s2_ETU004153');

        if(!$base){
            die('erreur de connexion a la base de donnée');
            
        }
        return $base;
    }
    
    function deconnection($base){
        mysqli_close($base);
    }
?>