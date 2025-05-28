function screenSize()
{
    var screenHeight = window.innerHeight;
    if (typeof(view1) != "undefined" && typeof(view2) != "undefined" && typeof(view3) != "undefined")
    {
        view1.style.height = screenHeight + "px";
        view2.style.height = screenHeight + "px";
        view3.style.height = screenHeight + "px";
        view4.style.height = screenHeight - 190 + "px";
    }
    else
    {
        view4.style.height = screenHeight - 190 + "px";
    }
}

function verify(place)
{
    if (place == 1)
    {
        let pass = document.getElementById("pass");
        let pass2 = document.getElementById("pass2");
        if (pass.value != pass2.value)
        {
            alert("Las Contraseñas no Coinciden, has Escrito: " + pass.value + " y " + pass2.value);
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        let pass = document.getElementById("pass3");
        let pass2 = document.getElementById("pass4");
        if (pass.value != pass2.value)
        {
            alert("Las Contraseñas no Coinciden, has Escrito: " + pass.value + " y " + pass2.value);
            return false;
        }
        else
        {
            return true;
        }
    }
}

function showIt()
{
    let update = document.getElementById('update');
    update.style.visibility = 'visible';
}

function showImg(src)
{
    var alertaImg = document.getElementById("alertaImg"); // La ID del botón del dialogo.
    var img = document.getElementById("show_pic"); // Asigno a la variable title el h4 con id title.
        
    img.src = src; // Muestro los mensajes en el diálogo.
    alertaImg.click(); // Lo hago aparecer pulsando el botón con ID alerta.
}

function toast(warn, ttl, msg) // Función para mostrar el Dialogo con los mensajes de alerta, recibe, Código, Título y Mensaje.
{
    var title = document.getElementById("title"); // Asigno a la variable title el h4 con id title.
    var message = document.getElementById("message"); // Asigno a la variable message el h5 con id message;
    if (warn == 1) // Si el código es 1, es una alerta.
    {
        title.style.backgroundColor = "#000000"; // Pongo los atributos, color de fondo negro.
        title.style.color = "yellow"; // Y color del texto amarillo.
    }
    else if (warn == 0) // Si no, si el código es 0 es un mensaje satisfactorio.
    {
        title.style.backgroundColor = "#FFFFFF"; // Pongo los atributos, color de fondo blanco.
        title.style.color = "blue"; // Y el color del texto azul.
    }
    else // Si no, viene un 2, es una alerta de error.
    {
        title.style.backgroundColor = "#000000"; // Pongo los atributos, color de fondo negro.
        title.style.color = "red"; // Y color del texto rojo.
    }
    title.innerHTML = ttl; // Muestro el Título del dialogo.
    message.innerHTML = msg; // Muestro los mensajes en el diálogo.
    alerta.click(); // Lo hago aparecer pulsando el botón con ID alerta.
}

function totNumPages()
{
    return Math.ceil(window.length / window.qtty);
}
    
    function prev()
    {
        if (window.page > 1)
        {
            window.page--;
            change(window.page, window.qtty, window.length);
        }
    }

    function next()
    {
        if (window.page < totNumPages())
        {
            window.page++;
            change(window.page, window.qtty, window.length);
        }
    }

    function change(page, qtty, length)
    {
        window.page = page;
        window.qtty = qtty;
        window.length = length;
        var btn_next = document.getElementById("next");
        var btn_prev = document.getElementById("prev");
        var TableList = document.getElementById("table");
        var page_span = document.getElementById("page");
        var html = "<table><tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>Teléfono</th><th>E-mail</th><th>Cumpleaños</th><th>Genero</th><th>Imágen de Perfil</th></tr><tr>";
        for (i = (page - 1) * qtty; i < page * qtty; i++)
        {
            if (i < length)
            {
                html += "<td>" + id[i] + "</td><td>" + username[i] + "</td><td>" + surname[i] + "</td><td>" + phone[i] + "</td><td>" + email[i] + "</td><td>" + bday[i] + "</td><td>" + genre[i] + "</td><td><a href='javascript:showImg(\"" + img[i] + "\")'><img src='" + img[i] + "' width='100' height='100' alt='Imágen de Perfil'></a></td></tr><tr>";
            }
        }
        html += "</tr></table>";
        TableList.innerHTML = html;
        page_span.innerHTML = "Página: " + page;
        if (length > 5)
        {
            if (page == 1)
            {
                btn_prev.style.visibility = "hidden";
            }
            else
            {
                btn_prev.style.visibility = "visible";
            }
            if (page == totNumPages())
            {
                btn_next.style.visibility = "hidden";
            }
            else
            {
                btn_next.style.visibility = "visible";
            }
        }
        else
        {
            btn_prev.style.visibility = "hidden";
            btn_next.style.visibility = "hidden";
        }
    }

var already = false; // already se usa para verificar si se seleccionó algún icono de las redes.

function changeAwesome(name) // Función para cambiar la imagen de los iconos de las redes sociales, recibe el nombre de la imagen.
{
    switch(name) // Cambia a la imagen seleccionada.
    {
        case "face": // Si es face.
            if (!already) // Si already es false, esta seleccionada la imagen de facebook.
            {
                face.className = "fa-brands fa-facebook-square"; // Muestra la imagen de facebook con el recuadro.
                already = true; // Pone already a true.
            }
            else // Si no.
            {
                face.className = "fa-brands fa-facebook-f"; // Muestra la imagen de Facebook solo la F.
                already = false; // Pone already a false;
            }
            break; // Rompe la ejecución del código.
        case "twit":
            if (!already)
            {
                twit.className = "fa-brands fa-twitter-square";
                already = true;
            }
            else
            {
                twit.className = "fa-brands fa-twitter";
                already = false;
            }
            break;
        case "goog":
            if (!already)
            {
                goog.className = "fa-brands fa-goodreads";
                already = true;
            }
            else
            {
                goog.className = "fa-brands fa-google";
                already = false;
            }
            break;
        case "inst":
            if (!already)
            {
                inst.className = "fa-brands fa-instagram-square";
                already = true;
            }
            else
            {
                inst.className = "fa-brands fa-instagram";
                already = false;
            }
            break;
        case "link":
            if (!already)
            {
                link.className = "fa-brands fa-linkedin";
                already = true;
            }
            else
            {
                link.className = "fa-brands fa-linkedin-in";
                already = false;
            }
            break;
        default:
            if (!already)
            {
                enve.className = "fa-solid fa-square-envelope";
                already = true;
            }
            else
            {
                enve.className = "fa-solid fa-envelope";
                already = false;
            }
            break;
    }
}