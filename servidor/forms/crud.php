<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/visualizador/servidor/repository/recursoRepository.php');
$recursos = obtenerRecursos();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/visualizador/servidor/forms/crud.css">
    <script src="http://localhost/visualizador/cliente/crud.js"></script>
    <title>CRUD del Visualizador</title>
</head>
<body>
    <div class="tabla">
            <h2>Recursos</h2>
            <div class="contenedor-crear-boton">
                <button 
                    class="boton-primario"
                    onclick="abrirModalCrearRecurso()"
                >+</button>
            </div>
            <table id="tablaRecursos">
                <tr>
                    <th>id</th>
                    <th>comienzo</th>
                    <th>finalizacion</th>
                    <th>prioridad</th>
                    <th>duracion</th>
                    <th>perfil</th>
                    <th>contenido</th>
                    <th>tipo</th>
                    <th>formato</th>
                    <th>Acciones</th>
                </tr>
                <?php 
                    // Listar recursos como filas de la tabla
                    // Cada fila es un formulario, para permitir ediciones en campos especificos
                    foreach ($recursos as $recursoActual){
                    ?> 
                    <tr>
                        <form id='editar-<?php echo $recursoActual->get_id(); ?>'>   
                            <td><input name="recursoId" size="2" value='<?php echo $recursoActual->get_id(); ?>' readonly/></td>
                            <td><input name="comienzo" size="5" value='<?php echo $recursoActual->get_comienzo(); ?>' type="datetime-local"/></td>
                            <td><input name="finalizacion" value='<?php echo $recursoActual->get_finalizacion(); ?>' type="datetime-local"/></td>
                            <td><input name="prioridad" min="1" max="3" style="width:2em;" value='<?php echo $recursoActual->get_prioridad(); ?>' type="number" /></td>
                            <td><input name="duracion" style="width:3em;" value='<?php echo $recursoActual->get_duracion(); ?>' type="number" /></td>
                            <td>
                                <select name="perfil" class="selector">
                                    <option value="1" <?php echo 1 == $recursoActual->get_perfil() ? 'selected' : '' ?> >Profesor</option>
                                    <option value="2" <?php echo 2 == $recursoActual->get_perfil() ? 'selected' : '' ?> >Alumno</option>
                                    <option value="3" <?php echo 3 == $recursoActual->get_perfil() ? 'selected' : '' ?> >Todos</option>
                                </select>
                            </td>
                            <td><?php echo $recursoActual->get_contenido(); ?></td>
                            <td><?php echo $recursoActual->get_tipo(); ?></td>
                            <td><?php echo $recursoActual->get_formato(); ?></td>
                            <td class="botones">
                                <button 
                                    type="button"
                                    class="edit-btn" 
                                    onclick=<?php echo 'editar(\'editar-' .  $recursoActual->get_id() .'\')';?> >Editar</button>
                                <button 
                                    type="button"
                                    class = "delete-btn"
                                    onclick=<?php echo'eliminar('.$recursoActual->get_id().')';?>
                                    >Eliminar</button>
                            </td>
                        </form > 
                    </tr>
                    <?php 
                    }
                ?>
            </table>
        </div>
        <div id="crearRecursoModal" class="modal">
            <div class="modal-contenido">
                <p class="titulo">Crear Recurso</p>
                <form id="crearRecurso">

                    <div class="conjunto">
                        <label>Comienzo</label>
                        <input 
                            type="datetime-local" 
                            id="comienzo"
                            name="comienzo"
                            >
                    </div>

                    <div class="conjunto">
                        <label>Finalización</label>
                        <input 
                            type="datetime-local" 
                            id="finalizacion"
                            name="finalizacion"
                            >
                    </div>

                    <div class="conjunto">
                        <label>Prioridad</label>
                        <input 
                            type="number"
                            value="1" 
                            min="1" max="3"
                            id="prioridad"
                            name="prioridad"
                            placeholder="1 es menor prioridad, 3 es mayor prioridad"
                            >
                    </div>

                    <div class="conjunto" >
                        <label>Duracion en segundos</label>
                        <input 
                            type="number"
                            value="15" 
                            id="duracion"
                            name="duracion"
                            >
                    </div>

                    <div class="conjunto">
                        <label>Perfil</label>
                        <select name="perfil" class="selector">
                            <option value="1">Profesor</option>
                            <option value="2">Alumno</option>
                            <option value="3">Todos</option>
                        </select>
                    </div>

                    <div class="conjunto">
                        <label>Contenido</label>
                        <select name="tipo" class="selector" onchange="seleccionarContenido(this.value)">
                            <option value="1">Web</option>
                            <option value="2">Imagen</option>
                            <option value="3">Video</option>
                        </select>
                    </div>


                    <div id="contenido" class="conjunto">
                        <label>Contenido Html</label>
                        <textarea 
                            type="text"  
                            name="contenido"
                            cols="64"
                            rows="3"
                            >
                        </textarea>
                    </div>

                    <div id="formato" class="conjunto" style="display:none;">
                        <label>Formato</label>
                        <select name="formato" class="selector">
                            <option value=""></option>
                            <option value="video/mp4">video/mp4</option>
                            <option value="video/av1">video/av1</option>
                            <option value="video/quicktime">video/quicktime</option>
                        </select>
                    </div>

                    <div id="fichero" class="conjunto" style="display:none;">
                        <label>Selecciona Recurso</label>
                        <input 
                            type="file"
                            name="fichero"
                            >
                    </div>

                    <button type="button" onclick="cerrarModalCrearRecurso()" class="boton-secundario">Cancelar</button>
                    <button type="button" onclick="crearRecurso()" class="boton-primario" >Crear</button>    
                </form>

            </div>
        </div>  
    </div>
    
    
</body>
</html>