<?php

static $conn;

function basedatos() {
    if(!isset($conn)) {
        $conn = new PDO('mysql:host=localhost;dbname=visualizador', 'ana', 'root');
    }
    return $conn;
}

?>