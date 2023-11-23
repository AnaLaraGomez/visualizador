<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/repository/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/entities/clienteRecurso.php');

    class ClienteRecursoRepository {

        public static function obtenerClienteRecursoPorId($clienteId) {
            $consultas = basedatos()->query("Select * from cliente_recurso where cliente_id = '$clienteId'");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return new ClienteRecurso(
                    $resultados->cliente_id,
                    $resultados->recursos,
                    $resultados->pos
                );
            }
        }

        public static function eliminarClienteRecursoPorId($clienteId) {
            basedatos()->exec("DELETE FROM `cliente_recurso` WHERE cliente_id = '$clienteId'");
        }

        public static function crearClienteRecurso($clienteRecurso) {
            $clienteId = $clienteRecurso->get_cliente();
            $recursos_str = $clienteRecurso->get_recursos_str();
            basedatos()->exec("
            INSERT INTO `cliente_recurso` (cliente_id, recursos) 
            VALUES ('$clienteId', '$recursos_str')");
        }

        public static function actualizarClienteRecurso($clienteRecurso) {
            $clienteId = $clienteRecurso->get_cliente();
            $pos = $clienteRecurso->get_pos();
            basedatos()->exec("UPDATE `cliente_recurso` SET  pos = $pos WHERE cliente_id = '$clienteId' ");
        }

    }
?>