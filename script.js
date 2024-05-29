    const SW = "sw_alumnos.php";

/**
 * Muestra el formulario en la página
 */
function displayForm(){
    var form = document.getElementById("form");
    form.style.display = "flex";
    form.style.flexDirection = "column";
}

/**
 * Introduce la información del alumno en el formulario
 * @param alumno alumno que se desea actualizar
 */
function setDataIntoForm(alumno){
    var input_dni = document.getElementById("dni");
    var input_apellido1 = document.getElementById("apellido1");
    var input_apellido2 = document.getElementById("apellido2");
    var input_nombre = document.getElementById("nombre");
    var input_direccion = document.getElementById("direccion");
    var input_localidad = document.getElementById("localidad");
    var input_provincia = document.getElementById("provincia");
    var input_fecha_nacimiento = document.getElementById("fecha_nacimiento");

    input_dni.value = alumno.dni;
    input_apellido1.value = alumno.apellido1;
    input_apellido2.value = alumno.apellido2;
    input_nombre.value = alumno.nombre;
    input_direccion.value = alumno.direccion;
    input_localidad.value = alumno.localidad;
    input_provincia.value = alumno.provincia;
    input_fecha_nacimiento.value = alumno.fecha_nacimiento;
}

/**
 * Devuelve el numero de registros que se deben mostrar en la tabla
 * @returns numero de registros a mostrar
 */
function getNumofRows(){
    var num_registros = document.getElementById("num_registros").value;
    return num_registros;
}

/**
 * Elimina todas las filas de la tabla que se le pase
 * @param table tabla que se desea limpiar 
 */
function cleanTable(table){
    while(table.rows.length > 0){
            table.deleteRow(0);
    }
}

/**
 * Recoge la informacion de un alumno y la inserta en el formulario
 * @param id id del alumno
 */
function getDataofAlumnos(dni){
    const data = {
        action: "get",
        filter_field: "DNI",
        filter_value: dni
    }
    fetch(SW, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then((response) => response.json())
    .then((response) => {
        if(response.error) {
            alert("Error: " + response.message);
        } else if (response.data && response.data.length > 0) {
            setDataIntoForm(response.data[0]);
        } else {
            alert("No se encontró el alumno con DNI: " + dni);
        }
    })
    .catch(error => {
        console.error("Error al obtener los datos del alumno:", error);
        alert("Error al obtener los datos del alumno. Por favor, revisa la consola para más detalles.");
    });
}

/**
 * Muestra el alumno
 * @param dni 
 */
function setFormForAlumno(dni){
    displayForm();
    getDataofAlumnos(dni);
}

/**
 * Muestra el formulario para la actualización de un registro
 */
function displayForm(){
    var form = document.getElementById("form");
    form.style.display = "flex";
    form.style.flexDirection = "column";
}

function sendForm(){
    //Recoger valores de los inputs del formulario
    var dni = document.getElementById("dni").value;
    var apellido1 = document.getElementById("apellido1").value;
    var apellido2 = document.getElementById("apellido2").value;
    var nombre = document.getElementById("nombre").value;
    var direccion = document.getElementById("direccion").value;
    var localidad = document.getElementById("localidad").value;
    var provincia = document.getElementById("provincia").value;
    var fecha_nacimiento = document.getElementById("fecha_nacimiento").value;

    var data = {
        action: "update",
        fields_to_update: {
            "dni": dni,
            "apellido1": apellido1,
            "apellido2": apellido2,
            "nombre": nombre,
            "direccion": direccion,
            "localidad": localidad,
            "provincia": provincia,
            "fecha_nacimiento": fecha_nacimiento,
        } 
    }

    fetch(SW, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    }).then(response => response.json())
    .then((response) => {
        console.log(response);
        if(response.data=="OK"){
            getAlumnos();
        }
    }).catch(error=>alert(error))
}

/**
 * Solicita todos los registros al sw y los muestra en una tabla
 */
function getAlumnos(pagina=1){
    var row_number = getNumofRows();
    if(row_number==0){
        //Valor por defecto
        row_number = 10;
    }
    const data = {
        action: "get",
        num_rows: row_number,
        n_pagina: pagina
    }
    fetch(SW, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        var tbody = document.getElementsByTagName("tbody")[0];
        cleanTable(tbody);
        for (var i = 0; i < data.data.length; i++){
            var tr = document.createElement("tr");

            //Creación de los td para los campos de la tabla
            var td_id = document.createElement("td");
            var td_dni = document.createElement("td");
            var td_apellido1 = document.createElement("td");
            var td_apellido2 = document.createElement("td");
            var td_nombre = document.createElement("td");
            var td_direccion = document.createElement("td");
            var td_localidad = document.createElement("td");
            var td_provincia = document.createElement("td");
            var td_fecha_nacimiento = document.createElement("td");
            var td_eliminar = document.createElement("td");
            var td_actualizar = document.createElement("td");

            //Agrega información a la tabla
            //Agrega información a la tabla
            td_id.innerHTML = data.data[i].DNI; // Usamos DNI como identificador único en lugar de id
            td_dni.innerHTML = data.data[i].DNI;
            td_apellido1.innerHTML = data.data[i].APELLIDO_1;
            td_apellido2.innerHTML = data.data[i].APELLIDO_2;
            td_nombre.innerHTML = data.data[i].NOMBRE;
            td_direccion.innerHTML = data.data[i].DIRECCION;
            td_localidad.innerHTML = data.data[i].LOCALIDAD;
            td_provincia.innerHTML = data.data[i].PROVINCIA;
            td_fecha_nacimiento.innerHTML = data.data[i].FECHA_NACIMIENTO;

            // Boton Actualizar y sus atributos
            var link_actualizar = document.createElement("button");
            link_actualizar.setAttribute('onclick', "setFormForAlumno('" + data.data[i].DNI + "')"); // Usamos DNI como identificador único
            link_actualizar.setAttribute("class", "actualizar");
            link_actualizar.setAttribute("id", data.data[i].DNI); // Usamos DNI como identificador único
            link_actualizar.innerHTML = "Actualizar";

            // Boton Eliminar y sus atributos
            var link_eliminar = document.createElement("button");
            link_eliminar.setAttribute('onclick', "eliminarAlumno(" + data.data[i].DNI + ")");
            link_eliminar.setAttribute("class", "eliminar");
            link_eliminar.innerHTML = "Eliminar";


            td_actualizar.appendChild(link_actualizar);
            td_eliminar.appendChild(link_eliminar);

            //Agregar tds al tr 
            tr.appendChild(td_id);
            tr.appendChild(td_dni);
            tr.appendChild(td_apellido1);
            tr.appendChild(td_apellido2);
            tr.appendChild(td_nombre);
            tr.appendChild(td_direccion);
            tr.appendChild(td_localidad);
            tr.appendChild(td_provincia);
            tr.appendChild(td_fecha_nacimiento);
            tr.appendChild(td_actualizar)
            tr.appendChild(td_eliminar);
            tbody.appendChild(tr);
        }
    }).catch(error=>alert(error))
}

/**
 * Solicita al SW eliminar un alumno de la base de datos
 * @param id id del alumno que se desea eliminar 
 */
function eliminarAlumno(dni) {
    var eliminar = confirm("¿Desea eliminar el alumno?");
    console.log("Deleting alumno with DNI: " + dni);
    if (eliminar) {
        const data = {
            action: 'delete',
            field: dni // Aquí se pasa el DNI como el campo 'field'
        }
        fetch(SW, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        }).then(
            response => response.json()
        ).then((response) => {
            console.log(response.data);
            alert("Se ha eliminado el alumno");
            getTotalCount();
            var rows_n = getNumofRows();

            var pagina = document.getElementById("paginador_num_registros").value;
            getAlumnos(rows_n * pagina);
        }).catch((error) => {
            alert(error);
        })
    } else {
        alert("No se ha eliminado el alumno");
    }
}



/**
 * Cambia de pagina y muestra los registros de esa pagina
 * @param paginador paginador que invoca la funcion 
 */
function cambiarPagina(paginador) {
    var paginaActual = document.getElementById("paginador_num_registros").value;
    const nPaginas = document.getElementById("nPaginas").value;
    const num_registros = document.getElementById("num_registros").value;
    if(paginador==1){
        paginaActual = 1;
        getAlumnos();
    } else if(paginador==2){
        if(Number(paginaActual)>1){
            paginaActual=Number(paginaActual)-1;
            if(paginaActual==1){
                getAlumnos();
            }
        }
        getAlumnos((paginaActual-1)*num_registros);
    } else if(paginador==3){
        paginaActual=Number(paginaActual)+1;
        if(Number(nPaginas)<paginaActual){
            paginaActual--;
        }
        getAlumnos((paginaActual-1)*num_registros);
    } else {
        console.log(nPaginas, document.getElementById("nPaginas").value);
        paginaActual = nPaginas;
        ultimoRegistro = nPaginas * num_registros;
        console.log("Ult. "+ultimoRegistro+" "+"Pagina act. "+" "+nPaginas+" "+"numRegistros. "+num_registros);
        getAlumnos(Number(nPaginas*num_registros));
    }
    document.getElementById("paginador_num_registros").value=paginaActual;
}

/**
 * Inserta en el documento HTML el total de registros de la tabla y el numero de paginas
 */
function getTotalCount() {
    const data = {
        action: "get",
        special_field: "count"
    }
    fetch(SW, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    }).then(response=>response.json())
    .then((response)=>{
        const nTotalRegistros=response.data[0]["count(*)"];
        const nFilas = getNumofRows();
        document.getElementById("total_registros").innerHTML=nTotalRegistros;
        document.getElementById("nPaginas").innerHTML=Math.ceil(nTotalRegistros/nFilas);
    }).catch(error=>alert(error))
}

/**
 * Muestra el formulario para insertar un alumno
 */
function displayFormForInsert() {
    var form = document.getElementById("formInsertar");
    if(form.style.display=="flex"){
        form.style.display="none";
    } else{
        form.style.display = "flex";
        form.style.flexDirection = "column"; 
    }
}

/**
 * Envía el formulario al SW para insertar un registro nuevo en la base de datos
 */
/**
 * Envía el formulario para insertar un nuevo alumno con depuración adicional
 */
/**
 * Envía el formulario para insertar un nuevo alumno con depuración adicional
 */
function sendFormInsert() {
    // Recoger los valores de los campos de entrada del formulario
    const dni = document.getElementById("dni_insert").value;
    const apellido1 = document.getElementById("apellido1_insert").value;
    const apellido2 = document.getElementById("apellido2_insert").value;
    const nombre = document.getElementById("nombre_insert").value;
    const direccion = document.getElementById("direccion_insert").value;
    const localidad = document.getElementById("localidad_insert").value;
    const provincia = document.getElementById("provincia_insert").value;
    const fecha_nacimiento = document.getElementById("fecha_nacimiento_insert").value;

    // Verificar que todos los campos requeridos están llenos
    if (!dni || !apellido1 || !nombre || !direccion || !localidad || !provincia || !fecha_nacimiento) {
        alert("Por favor, complete todos los campos requeridos.");
        return;
    }

    // Crear el objeto de datos para enviar al servidor
    const data = {
        action: "insert",
        values: {
            dni: dni,
            apellido1: apellido1,
            apellido2: apellido2,
            nombre: nombre,
            direccion: direccion,
            localidad: localidad,
            provincia: provincia,
            fecha_nacimiento: fecha_nacimiento
        }
    };

    console.log("Enviando datos al servidor:", data);  // Log de depuración

    // Enviar los datos al servidor utilizando fetch
    fetch(SW, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log("Respuesta del servidor recibida:", response);  // Log de depuración
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            throw new Error("Error en la solicitud al servidor");
        }
        return response.json();
    })
    .then((response) => {
        console.log("Datos de respuesta del servidor:", response);  // Log de depuración
        if (!response || !response.data) {
            throw new Error("La respuesta del servidor no es válida");
        }
        if (response.data === "OK") {
            alert("Alumno insertado correctamente");
            // Opcionalmente, recargar la lista de alumnos o resetear el formulario
            loadAlumnos();
            document.getElementById("formInsertar").reset();
        } else {
            alert("Hubo un problema al insertar el alumno: " + response.data);
        }
    })
    .catch((error) => {
        console.error("Error en la inserción del alumno:", error);  // Log de depuración
        alert("Hubo un error: " + error.message);
    });
}

