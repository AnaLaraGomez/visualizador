<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/repository/clienteRecursoRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/repository/perfilRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/entities/clienteRecurso.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/repository/recursoRepository.php');
class Dispatcher {
    public static function next($clienteId, $perfil) {
        // Tenemos lista de recursos para este cliente?
        $clienteRecurso = ClienteRecursoRepository::obtenerClienteRecursoPorId($clienteId);
        if(empty($clienteRecurso)) {
            $clienteRecurso = Dispatcher::generate($clienteId, $perfil);
        }

        $recursoId = $clienteRecurso->get_recurso_en_pos();
        // recuperar de base de datos
        $recurso = obtenerRecurso($recursoId);
        $clienteRecurso->incrementar_pos();

        if($clienteRecurso->ha_terminado()) {
            ClienteRecursoRepository::eliminarClienteRecursoPorId($clienteId);
        } else {
            ClienteRecursoRepository::actualizarClienteRecurso($clienteRecurso);
        }

        return $recurso;
    }

    public static function generate($clienteId, $perfil) {
        // Necesitamos cargar una lista de recusos para este cliente
        $listaRecursosBarajada =  Dispatcher::obtenerListaRecursosPorPerfilPrioridad($perfil);
        $clienteRecurso = new ClienteRecurso($clienteId, implode(',', $listaRecursosBarajada), 0);
        ClienteRecursoRepository::crearClienteRecurso($clienteRecurso);
        return $clienteRecurso;
    }

    public static function obtenerListaRecursosPorPerfilPrioridad($perfil) {
        $perfilId = obtenerPerfilIdPorNombre($perfil);
        // obtener todos los recursos para ese perfil
        $recursos = obtenerRecursoPorPerfil($perfilId);
        // elegir uno aleatoriamente teniendo en cuenta las prioridades como probabiliddad de eleccion
        $recursosDuplicadosSoloId = array();
        foreach($recursos as $recurso) {
            for ($i = 0; $i < $recurso->get_prioridad(); $i++) {
                $recursosDuplicadosSoloId[] = $recurso->get_id();
            }
        }
        // Barajamos la lista
        shuffle($recursosDuplicadosSoloId);
        return $recursosDuplicadosSoloId;
    
    }
}

?>