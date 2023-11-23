<?php
class ClienteRecurso {
    private $cliente;
    private $recursos;
    private $pos;

    function __construct($cliente, $recursos_str, $pos) {
        $this->cliente = $cliente;
        $this->recursos = explode(',', $recursos_str);
        $this->pos = $pos;

    }

    function get_cliente() {
        return $this->cliente;
    }
    
    function get_recursos() {
        return $this->recursos;
    }

    function get_recursos_str() { // Para usarla en el repository
        return implode(",", $this->recursos);
    }

    function get_recurso_en_pos() { // Para el Dispatcher
        return $this->recursos[$this->pos];
    }

    function get_pos () {
        return $this->pos;
    }

    function incrementar_pos() { // Para el Dispatcher
        return $this->pos = $this->pos +1;
    }

    function ha_terminado() {
        return $this->pos >= sizeof($this->recursos);
    }

}
?>