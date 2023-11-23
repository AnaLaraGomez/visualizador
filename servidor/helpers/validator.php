<?php 

function campoRequerido($campo, $claveError, $mensajeError, $errores) {
    if(empty($campo) ){
        $errores[$claveError]=$mensajeError;
    }
    return $errores;
}

?>