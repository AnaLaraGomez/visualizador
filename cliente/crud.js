function editar(formularioId) {
    let rowId = 'tr-' + formularioId.split('-')[1];
    limpiarFormularioEditado(rowId);
    let formularioAEnviar = document.getElementById(formularioId);
    let recursoEditado = convertirFormularioEnJson(formularioAEnviar);
    
    console.log('recursoEditado', recursoEditado);

    let url = `http://localhost/visualizador/servidor/api/apiRecurso.php?recursoId=${recursoEditado.recursoId}`;
    let opciones = {
        method: "PUT",
        body: JSON.stringify(recursoEditado)
    };

    fetch(url, opciones)
    .then((respuesta) => respuesta.json())
    .then((respuestaEnJson) => {
        if(respuestaEnJson.succeed) {
            location.reload();
        }
    });
}

function eliminar(recursoId) {
    let url = `http://localhost/visualizador/servidor/api/apiRecurso.php?recursoId=${recursoId}`;
    let opciones = {
        method: "DELETE"
    };

    fetch(url, opciones)
    .then((respuesta) => respuesta.json())
    .then((respuestaEnJson) => {
        if(respuestaEnJson.succeed) {
            location.reload();
        }
    });
}

function abrirModalCrearRecurso() {
    let crearRecursoModal = document.getElementById('crearRecursoModal');
    crearRecursoModal.style.visibility = 'visible';
}

function cerrarModalCrearRecurso() {
    let crearRecursoModal = document.getElementById('crearRecursoModal');
    crearRecursoModal.style.visibility = 'hidden';
}

function crearRecurso() {
    let url = 'http://localhost/visualizador/servidor/api/apiRecurso.php';
    let formularioAEnviar = document.getElementById("crearRecurso");

    let opciones = {
        method: "POST",
        body: new FormData(formularioAEnviar),
    };

    fetch(url, opciones)
    .then((respuesta) => respuesta.json())
    .then((respuestaEnJson) => {
        limpiaErrores();
        if(respuestaEnJson.succeed) {
            location.reload();
        } else {
            
        let grupoComienzo = document.getElementById("grupoComienzo");
        crearError(grupoComienzo,respuestaEnJson['comienzo']);
        let grupoFinalizacion = document.getElementById("grupoFinalizacion");
        crearError(grupoFinalizacion,respuestaEnJson['finalizacion']);

        let grupoPrioridad = document.getElementById("grupoPrioridad");
        crearError(grupoPrioridad,respuestaEnJson['prioridad']);
        let grupoDuracion = document.getElementById("grupoDuracion");
        crearError(grupoDuracion,respuestaEnJson['duracion']);
        let grupoPerfil = document.getElementById("grupoPerfil");
        crearError(grupoPerfil,respuestaEnJson['perfil']);

        let grupoTipo = document.getElementById("grupoTipo");
        crearError(grupoTipo,respuestaEnJson['tipo']);

        let grupoContenido = document.getElementById("contenido");
        crearError(grupoContenido,respuestaEnJson['contenido']);

        let grupoFormato = document.getElementById("formato");
        crearError(grupoFormato,respuestaEnJson['formato']);

        }
    });
}

function seleccionarContenido(tipoContenido) {
    let contenido = document.getElementById('contenido');
    let formato = document.getElementById('formato');
    let fichero = document.getElementById('fichero');
    console.log(tipoContenido);
    switch(tipoContenido) {
        case '1': // Web
            contenido.style.display= 'flex';
            contenido.classList.add('conjunto')

            formato.style.display= 'none';
            formato.classList.remove('conjunto')
            
            fichero.style.display= 'none';
            fichero.classList.remove('conjunto')
            break;

        case '2': // Imagen
            contenido.classList.remove('conjunto')
            contenido.style.display= 'none';
         
            formato.style.display= 'none';
            formato.classList.remove('conjunto')
         
            fichero.style.display= 'flex';
            fichero.classList.add('conjunto')
            break;

        case '3': // Video
            contenido.style.display= 'none';
            contenido.classList.remove('conjunto')

            formato.style.display= 'flex';
            formato.classList.add('conjunto')

            fichero.style.display= 'flex';
            fichero.classList.add('conjunto')

            break;
    }
}

function convertirFormularioEnJson(formularioAEnviar){
    // Fuente: https://www.learnwithjason.dev/blog/get-form-values-as-json/
    const data = new FormData(formularioAEnviar);
    return Object.fromEntries(data.entries());
}

function crearError(grupo, error){
    if(error) {
        let parrafoError = document.createElement("p");
        let texto = document.createTextNode(error);
        parrafoError.append(texto);
        parrafoError.classList.add("error");
        grupo.appendChild(parrafoError);
    }
}

function limpiaErrores() {
    // Por alguna razon, hay que clonar la lista para que no se 
    // lie con los otros errores que se crearan despues.
    let errores = [...document.getElementsByClassName("error")];
    for (const elementoActual of errores) {
        elementoActual.remove(); 
    }
}

function formularioEditado(rowId) {
    console.log(rowId);
    let row = document.getElementById(rowId);
    row.style.border=" 2px solid red";
}

function limpiarFormularioEditado(rowId) {
    console.log(rowId);
    let row = document.getElementById(rowId);
    row.style.border="unset";
}