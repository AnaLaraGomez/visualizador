<?php 
// Solo por hacer esto, todas las peticiones setean uuna cookie en el navegador.
// Esa cookie es la PHPSESSID que usaremos como identificador del cliente/televisor
session_start();

class SessionHelper {
    public static function  obtenerIdentificadorDelcliente() {
        return session_id(); 
    }
}
?>