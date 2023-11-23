<?php
class Recurso {
    // Properties
    private $id;
    private $comienzo;
    private $finalizacion;
    private $prioridad;
    private $duracion;
    private $perfil;
    private $contenido;
    private $tipo;
    private $formato;
    

    // Constructor
    function __construct($id, $comienzo, $finalizacion, $prioridad, $duracion, $perfil, $contenido, $tipo, $formato) {
      $this->id = $id;
      $this->comienzo = $comienzo;
      $this->finalizacion = $finalizacion;
      $this->prioridad = $prioridad;
      $this->duracion = $duracion;
      $this->perfil = $perfil;
      $this->contenido = $contenido;
      $this->tipo = $tipo;
      $this->formato = $formato;  
    }
  
    // Methods
    function get_id() {
      return $this->id;
    }

    function get_comienzo() {
      return $this->comienzo;
    }

    function get_finalizacion() {
      return $this->finalizacion;
    }

    function get_prioridad() {
      return $this->prioridad;
    }

    function get_duracion() {
      return $this->duracion;
    }

    function get_perfil() {
      return $this->perfil;
    }

    function get_contenido() {
      return $this->contenido;
    }

    function get_tipo() {
      return $this->tipo;
    }

    function get_formato() {
      return $this->formato;
    }

    function set_comienzo($comienzo) {
      $this->comienzo = $comienzo;
    }

    function set_finalizacion($finalizacion) {
      $this->finalizacion = $finalizacion;
    }

    function set_prioridad($prioridad) {
      $this->prioridad = $prioridad;
    }

    function set_duracion($duracion) {
      $this->duracion = $duracion;
    }

    function set_perfil($perfil) {
      $this->perfil = $perfil;
    }

    function set_contenido($contenido) {
      $this->contenido = $contenido;
    }

    function set_tipo($tipo) {
      $this->tipo = $tipo;
    }
    
    function set_formato($formato) {
      $this->formato = $formato;
    }

    function to_json() {
      return '{'.
        '"id":' . $this->id . ',' .
        '"comienzo":"'.$this->comienzo .'",' .
        '"finalizacion":"'.$this->finalizacion .'",' .
        '"prioridad":'.$this->prioridad .',' .
        '"duracion":'.$this->duracion .',' .
        '"perfil":'.$this->perfil .',' .
        '"contenido":"'.$this->contenido .'",' .
        '"tipo":'.$this->tipo .',' .
        '"formato":"'.$this->formato .'"' .
        '}';
    }

    
  }
?>