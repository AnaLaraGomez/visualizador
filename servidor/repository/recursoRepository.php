<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/repository/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/entities/recurso.php');

    function crearRecurso($comienzo, $finalizacion, $prioridad, $duracion, $perfil, $contenido, $tipo, $formato) {
        $comienzo = str_replace('T', ' ', $comienzo);
        $finalizacion = str_replace('T', ' ', $finalizacion);
        basedatos()->exec("
        INSERT INTO `recurso` (comienzo, finalizacion, prioridad, duracion, perfil_id, contenido, tipo_id, formato) 
        VALUES ('$comienzo', '$finalizacion', $prioridad, $duracion, $perfil, '$contenido', $tipo, '$formato')");

    }

    function obtenerRecurso($recursoId) {
        $consultas = basedatos()->query("Select r.*, r.tipo_id as tipo, r.perfil_id as perfil from recurso r where id = $recursoId" );
        while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
             return new Recurso(
                $resultados->id,
                $resultados->comienzo,
                $resultados->finalizacion,
                $resultados->prioridad,
                $resultados->duracion,
                $resultados->perfil,
                $resultados->contenido,
                $resultados->tipo,
                $resultados->formato
            );
        }
    }
    
    function obtenerRecursoPorPerfil($perfilId) {
        $consultas = basedatos()->query(
            "Select r.*, r.tipo_id as tipo, r.perfil_id as perfil from recurso r 
            WHERE perfil_id in ( $perfilId , 3) 
            AND comienzo < NOW() 
            AND finalizacion > NOW()" 
        );
        $recursos = array();
        while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
            $recursos[] =  new Recurso(
                $resultados->id,
                $resultados->comienzo,
                $resultados->finalizacion,
                $resultados->prioridad,
                $resultados->duracion,
                $resultados->perfil,
                $resultados->contenido,
                $resultados->tipo,
                $resultados->formato
            );
        }
        return $recursos;
    }

    function obtenerRecursos() {
        $consultas = basedatos()->query("Select r.*, r.tipo_id as tipo, r.perfil_id as perfil from recurso r" );
        $recursos = array();
        while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
             $recursos[] = new Recurso(
                $resultados->id,
                $resultados->comienzo,
                $resultados->finalizacion,
                $resultados->prioridad,
                $resultados->duracion,
                $resultados->perfil,
                $resultados->contenido,
                $resultados->tipo,
                $resultados->formato
            );
        }
        return $recursos;
    }

    function actualizarRecurso($recursoAModificar) {
        $recursoId = $recursoAModificar->get_id();
        $comienzo = $recursoAModificar->get_comienzo();
        $finalizacion = $recursoAModificar->get_finalizacion();
        $prioridad = $recursoAModificar->get_prioridad();
        $duracion = $recursoAModificar->get_duracion();
        $perfil = $recursoAModificar->get_perfil();
        $contenido = $recursoAModificar->get_contenido();
        $tipo = $recursoAModificar->get_tipo();
        $formato = $recursoAModificar->get_formato();
        
        $comienzo = str_replace('T', ' ', $comienzo);
        $finalizacion = str_replace('T', ' ', $finalizacion);

        basedatos()->exec("UPDATE `recurso` SET  comienzo = '$comienzo', finalizacion = '$finalizacion', 
        prioridad = $prioridad, duracion = $duracion, perfil_id = $perfil, 
        contenido = '$contenido', tipo_id = $tipo, formato = '$formato'  WHERE id = $recursoId ");
    }

    function eliminarRecurso($recursoId) {
        basedatos()->exec("DELETE FROM `recurso` WHERE id = $recursoId");
    }

    function eliminarRecursoCaducado() {
        basedatos()->exec("DELETE FROM `recurso` WHERE finalizacion < now() - INTERVAL 1 DAY");
    }
?>