<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/helpers/validator.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/entities/recurso.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/helpers/sessionHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/components/dispatcher.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    // Crear
    $comienzo = $_POST['comienzo'];
    $finalizacion = $_POST['finalizacion'];
    $prioridad = $_POST['prioridad'];
    $duracion = $_POST['duracion'];
    $perfil = $_POST['perfil'];
    $contenido = $_POST['contenido'];
    $tipo = $_POST['tipo'];
    $formato = $_POST['formato'];

    $erroresValidacion=array();
    $erroresValidacion=campoRequerido($comienzo, 'comienzo',"Este campo es obligatorio", $erroresValidacion);
    $erroresValidacion=campoRequerido($finalizacion, 'finalizacion',"Este campo es obligatorio", $erroresValidacion);
    $erroresValidacion=campoRequerido($prioridad, 'prioridad',"Este campo es obligatorio", $erroresValidacion);
    $erroresValidacion=campoRequerido($duracion, 'duracion',"Este campo es obligatorio", $erroresValidacion);
    $erroresValidacion=campoRequerido($perfil, 'perfil',"Este campo es obligatorio", $erroresValidacion);
    $erroresValidacion=campoRequerido($tipo, 'tipo',"Este campo es obligatorio", $erroresValidacion);

    if($tipo == '3') { // video
        $erroresValidacion=campoRequerido($formato, 'formato',"Este campo es obligatorio", $erroresValidacion);
    }

    if($tipo == '1') {
        $erroresValidacion=campoRequerido($contenido, 'contenido',"Este campo es obligatorio", $erroresValidacion);
    }

    if(!empty($erroresValidacion)) {
        $erroresValidacion['succeed'] = false;
        echo json_encode($erroresValidacion);
        return;
    }


    if($tipo ==  '2' || $tipo ==  '3' ) { //  imagen o video
        // Leer el fichero adjunto
        if(!isset($_FILES['fichero'])) {
            $erroresValidacion['succeed'] = false;
            $erroresValidacion['fichero'] = 'Es obligatorio adjuntar un fichero';
            echo json_encode($erroresValidacion);
            return;
        }
        
        $tipoFichero = $_FILES['fichero']['type'];
        $extension = explode('/', $tipoFichero)[1];
        $nombreAleatorioDelFichero= uniqid() . ".$extension";
        if($tipo ==  '2') {
            $carpeta = 'imagenes';
        } else {
            $carpeta = 'videos';
        }
        move_uploaded_file($_FILES['fichero']['tmp_name'],"../../archivos/$carpeta/$nombreAleatorioDelFichero");
        $contenido = "/visualizador/archivos/$carpeta/$nombreAleatorioDelFichero";
    } else {
        // Eliminar saltos de linea para no romper el json
        $contenido = str_replace(array("\r", "\n"), '', $contenido);
        // Fuente: https://stackoverflow.com/questions/1332240/why-cant-php-just-convert-quotes-to-html-entities-for-mysql
        $contenido = htmlspecialchars($contenido);
    }

    crearRecurso($comienzo, $finalizacion, $prioridad, $duracion, $perfil, $contenido, $tipo, $formato);

    $respuesta ['succeed'] = true;
    echo json_encode($respuesta);
    return;

} elseif ($_SERVER['REQUEST_METHOD']=='PUT') {
    // Actualizar
    $respuesta = array();
    if(!isset($_GET['recursoId'])) {
        $respuesta ['succeed'] = false;
        echo json_encode($respuesta);
        return;
    }

    // recuperar de base de datos
    $recurso = obtenerRecurso($_GET['recursoId']);
    if(empty($recurso)) {
        $respuesta ['succeed'] = false;
        echo json_encode($respuesta);
        return;
    }

    // modificarlo
    $body = file_get_contents("php://input");
    $recursoEditado = json_decode($body);


    if(!empty($recursoEditado->comienzo)) {
        $recurso->set_comienzo($recursoEditado->comienzo);
    }


    if(!empty($recursoEditado->finalizacion)) {
        $recurso->set_finalizacion($recursoEditado->finalizacion);
    }

    if(!empty($recursoEditado->prioridad)) {
        $recurso->set_prioridad($recursoEditado->prioridad);
    }

    if(!empty($recursoEditado->duracion)) {
        $recurso->set_duracion($recursoEditado->duracion);
    }

    if(!empty($recursoEditado->perfil)) {
        $recurso->set_perfil($recursoEditado->perfil);
    }

    if(!empty($recursoEditado->contenido)) {
        $recurso->set_contenido($recursoEditado->contenido);
    }

    if(!empty($recursoEditado->tipo)) {
        $recurso->set_tipo($recursoEditado->tipo);
    }

    if(!empty($recursoEditado->formato)) {
        $recurso->set_formato($recursoEditado->formato);
    }

    if(isset($_FILES['fichero'])) {
        $tipoFichero = $_FILES['fichero']['type'];
        $extension = explode('/', $tipoFichero)[1];
        $nombreAleatorioDelFichero= uniqid() . ".$extension";
        if($tipo ==  '2') {
            $carpeta = 'imagenes';
        } else {
            $carpeta = 'videos';
        }

        move_uploaded_file($_FILES['fichero']['tmp_name'],"../../archivos/$carpeta/$nombreAleatorioDelFichero");
        $contenido = "/visualizador/archivos/$carpeta/$nombreAleatorioDelFichero";
        $recurso->set_contenido($contenido);

    }


    // Volcarlo a base de datos
    actualizarRecurso($recurso);

    $respuesta ['succeed'] = true;
    echo json_encode($respuesta);
    return;

} elseif ($_SERVER['REQUEST_METHOD']=='GET') {
    // Se ejecuta varias veces por minuto mientras esten las pantallas encendidas
    // Es un sistema que nos permitira hacer una comprobacion continua de cuales
    // son los recursos caducados y eliminarlos
    // Limpieza de recursos caducados
    eliminarRecursoCaducado();

    // Listar o Obtener para visualizacion
    if(isset($_GET['all'])) {
        $recursos = obtenerRecursos();
        echo json_encode($recursos);
        return;
    }

    if(isset($_GET['perfil'])) {
        $perfil = $_GET['perfil'];
        // Obtenemos identificador del cliente/TV
        $clienteId = SessionHelper::obtenerIdentificadorDelcliente();
        $recursoElegido = Dispatcher::next($clienteId, $perfil);
        echo $recursoElegido->to_json();
        return;
    }


} elseif ($_SERVER['REQUEST_METHOD']=='DELETE') {
    // Eliminar
    $respuesta = array();
    if(!isset($_GET['recursoId'])) {
        $respuesta ['succeed'] = false;
        echo json_encode($respuesta);
        return;
    }
    eliminarRecurso($_GET['recursoId']);

    $respuesta ['succeed'] = true;
    echo json_encode($respuesta);
    return;
}


?>