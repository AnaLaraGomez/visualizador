<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/repository/conexion.php');

    function obtenerPerfilIdPorNombre($perfil) {
        $consultas = basedatos()->query("Select id from perfil  where nombre = '$perfil'" );
        while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
             return $resultados->id;
        }
    }

?>