window.addEventListener("load", function() {

    // Hacer llamada fetch en funcion del query param que me pasen en location
    obtenerRecurso();


    function obtenerPerfil() {
        let perfil = document.location.href.split('?')[1];
        switch(perfil.toLowerCase()) {
            case 'profesor':
            case 'alumno':
                return perfil;
            default:
                return '';    
        }
    }

    function obtenerRecurso() {
        let perfil = obtenerPerfil();
        let url = `http://localhost/visualizador/servidor/api/apiRecurso.php?perfil=${perfil}`
        fetch(url)
        .then((respuesta) => respuesta.json())
        .then((respuestaEnJson) => {
            // Pintar el contenido
            pintarRecurso(respuestaEnJson);

            // setTimeout para volver a llamar
            setTimeout(() => {
                obtenerRecurso();
            }, respuestaEnJson.duracion * 1000);
        });
    }

    function pintarRecurso(recurso) {    
        let contenido = this.document.getElementById('contenido');
        let width = screen.width;
        let height = screen.height;
        contenido.innerHTML = '';
        switch(recurso.tipo) {
            case 1: // Web
                contenido.innerHTML = decodeHtml(recurso.contenido);
                break;
            case 2: // Imagen 
                let img = document.createElement('img');
                img.src = document.location.origin + recurso.contenido;
                img.style.maxWidth = width
                img.style.maxHeight = height
                contenido.appendChild(img);
                break;
            case 3: // Video
                let video = document.createElement('video');
                video.src = document.location.origin + recurso.contenido;
                video.type = recurso.formato;
                video.style.maxWidth = width
                video.style.maxHeight = height
                video.setAttribute('autoplay', '');
                contenido.appendChild(video);
                break;
        }
    }

    // Fuente: https://gist.github.com/daraul/7057c25495dc0284d1c4e77997d25938
    function decodeHtml(text) {
        var map = {
            '&amp;': '&',
            '&#038;': "&",
            '&lt;': '<',
            '&gt;': '>',
            '&quot;': '"',
            '&#039;': "'",
            '&#8217;': "’",
            '&#8216;': "‘",
            '&#8211;': "–",
            '&#8212;': "—",
            '&#8230;': "…",
            '&#8221;': '”'
        };
    
        return text.replace(/\&[\w\d\#]{2,5}\;/g, function(m) { return map[m]; });
    };

});