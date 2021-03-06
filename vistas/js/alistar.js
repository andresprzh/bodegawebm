$(document).ready(function () {

    /* ============================================================================================================================
                                                        INICIALIZACION   
    ============================================================================================================================*/

    // INICIAR TABS
    $('.tabs').tabs({ 'swipeable': false });

    // pone requisiciones en el input select
    $.ajax({
        url: "api/alistar/requisiciones",
        method: "GET",
        data: '',
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (res) {
            // SE MUESTRAN LAS reqUISICIONES EN EL MENU DE SELECCION
            for (var i in res) {

                $("#requeridos").append($('<option value="' + res[i] + '">' + res[i] + '</option>'));

            }

        }
    });

    /* ============================================================================================================================
                                                        EVENTOS   
    ============================================================================================================================*/

    //EVENTO AL CAMBIAR ENTRADA REQUERIDOS
    $(".requeridos").change(function (e) {

        $.when(mostrarItems()).done(function () {

            $.when(mostrarCaja()).done(function () {
                $("#codbarras").focus();
            });
        });

    });

    // FUNCION QUE FILTRA ITEMS POR UBICACION
    $("#ubicacion").change(function (e) {

        cambiarUbicacion();
        $("#codbarras").focus();
    });

    // EVENTO INPUT  CODIGO DE BARRAS
    $("#codbarras").keypress(function (e) {

        //si se presiona enter busca el item y lo pone en la pagina
        if (e.which == 13) {
            let ubicacion = $('#ubicacion').val();
            $.when(buscarCodbar()).done(function () {
                $.when(mostrarItems()).done(function () {
                    $('#ubicacion').val(ubicacion);
                    cambiarUbicacion();

                });
            });


        }

    });

    // EVENTO CUANDO SE ESCRIBE EN EL INPUT DE LA TABLA EDITABLE(EVITA QUE SE DIGITEN LETRAS)
    $('#tablaeditable').on('keydown', 'input', function (e) {

        // permite: spacio, eliminar , tab, escape, enter y  .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // permite: Ctrl+letra, Command+letra
            ((e.keyCode >= 0) && (e.ctrlKey === true || e.metaKey === true)) ||
            // permite: home, fin, izquierda, derecha, abajo, arriba
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // no hace nada si cumple la condicion
            return;
        }
        // solo acepta numeros
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            //previene mandar los datos al input
            e.preventDefault();
        }
    });

    // EVENTO CUANDO SE CAMBIA  EL INPUT DE LA TABLA EDITABLE(EVITA QUE SE DIGITEN LETRAS)
    $('#tablaeditable').on('change', 'input', function (e) {
        let alistados = $(this).val();
        const pedido = $('td:nth(1)', $(this).parents('tr')).text();

        // consigue el valor maximo en decenas que puede valer la cantidad de alistados
        // EJ: entre 0 y 10 maximo valor=10, entre 11 y 100 maximo valor 100
        let a = 0;
        let pot = pedido;
        do {
            a++;
            pot = pot / 10
        } while (pot > 1);
        let maxvalue = Math.pow(10, a)

        if (alistados > maxvalue * 10) {
            var toastHTML = `<p class="truncate black-text"><i class="fas fa-exclamation-circle"></i> Revisar cantidad alistada</span></p>`;
            M.toast({
                html: toastHTML, classes: "red lighten-2'",
                //  displayLength: 500 
            });
            $(this).parents('tr').removeClass('yellow lighten-2');
            $(this).parents('tr').addClass('red lighten-2');

        } else if (alistados > maxvalue) {
            var toastHTML = `<p class="truncate black-text"><i class="fas fa-exclamation-circle"></i> Revisar cantidad alistada</span></p>`;
            M.toast({
                html: toastHTML, classes: "yellow lighten-2'",
                //  displayLength: 500 
            });

            $(this).parents('tr').removeClass('red lighten-3');
            $(this).parents('tr').addClass('yellow lighten-2');
            $(this).parents('tr').attr('title', 'Revisar Cantidad Alistada');
        } else {

            $(this).parents('tr').removeClass('yellow lighten-2');
            $(this).parents('tr').removeClass('red lighten-3');
            $(this).parents('tr').attr('title', '');
        }

        // console.log(maxvalue);
    });

    // EVENTO SI SE PRESIONA 1 TECLA EN LA TABLA EDITABLE
    $('#tablaeditable').on('keydown', 'input', function (e) {
        //previene enviar formulario si se presiona enter
        if (e.which == 13) {
            e.preventDefault();
        }
    });

    // EVENTO SI SE PRESIONA 1 BOTON EN LA TABLA EDITABLE(ELIMINAR ITEM)
    $('#tablaeditable').on('click', 'button', function (e) {
        //consigue el numero de requerido
        e.preventDefault();
        var requeridos = $(".requeridos").val();
        //id usuario es obtenida de las variables de sesion
        var req = [requeridos, id_usuario];

        // se consigue el id del item y el nombre
        let iditem = $(this).closest('tr').attr('id');
        const nomitem = $('td:first', $(this).parents('tr')).text();;


        // Pregunta si se elimina el item
        swal({
            title: `¿Quitar item ${nomitem}?`,
            icon: "warning",
            buttons: ['Cancelar', 'Quitar']
        })
            .then((Quitar) => {

                if (Quitar) {

                    // elimina el item y vuelve a cargar la tabla de vista
                    let ubicacion = $('#ubicacion').val();
                    $.when(eliminarItem(iditem, req)).done(function () {
                        $.when(mostrarItems()).done(function () {
                            $('#ubicacion').val(ubicacion);
                            cambiarUbicacion();
                        });
                    });

                }

            });
    });

    // EVENTO SI SE ENVIA EL FORMULARIO DE LA TALA EDITABLE
    $('#formalistados').submit(function (e) {
        e.preventDefault();
        //consigue el numero de requerido
        let requeridos = $(".requeridos").val();
        //id usuario es obtenida de las variables de sesion
        let req = [requeridos, id_usuario];




        //si se presiona aceptar se continua con el proceso
        swal({
            title: "¿Cerrar caja?",
            icon: "warning",
            buttons: ['Cancelar', 'Cerrar']
        }).then((Cerrar) => {

            //si se le da click en cerrar procede a pasar los items a la caja y a cerrarla
            if (Cerrar) {

                // Busca los datos en la tabla
                let table = document.getElementById("tablaeditable");
                let tr = table.getElementsByTagName("tr");
                let items = new Array;

                for (let i = 0; i < tr.length; i++) {

                    items[i] = {
                        "iditem": tr[i].id,
                        "alistados": $(tr[i]).find("input").val(),
                    };
                }

                //guarda el tipo de caja en una variable
                var tipocaja = $("#caja").val();


                $.ajax({
                    url: 'api/alistar/empacar',//url de la funcion
                    method: 'post',//metodo post para mandar datos
                    data: { 'req': req, "tipocaja": tipocaja, "items": items },//datos que se enviaran 
                    dataType: 'JSON',
                    success: function (res) {
                        if (res) {

                            swal("¡Caja cerrada exitosamente!", {
                                icon: "success",
                            }).then((event) => {

                                location.reload(true);

                            });

                        } else {

                            swal("¡Error al cerrar la caja!", {
                                icon: "error",
                            });

                        }

                    }
                });
            }
        });

    });
});



/* ============================================================================================================================
                                                FUNCIONES   
============================================================================================================================*/

// FUNCION QUE BUSCA EL CODIGO DE BARRAS
function buscarCodbar() {

    //consigue el codigo de barras
    let codigo = $('#codbarras').val();
    //consigue el numero de requerido
    let requeridos = $(".requeridos").val();
    //id usuario es obtenida de las variables de sesion
    let req = [requeridos, id_usuario];

    // ajax para ejecutar un script php mandando los datos
    return $.ajax({
        // url: 'ajax/alistar.items.ajax.php',//url de la funcion
        url: 'api/alistar/items',//url de la funcion
        type: 'post',//metodo post para mandar datos
        data: { "codigo": codigo, "req": req },//datos que se enviaran
        dataType: 'JSON',
        success: function (res) {
            // console.log(res);
            // return 0;
            agregarItem(res);
            $('#codbarras').val("");
            $("#codbarras").focus();
        }
    });


}

// FUNCIONQ UE QUITA UN ITEM DE LA CAJA
function eliminarItem(iditem, req) {

    return $.ajax({
        type: "POST",
        // url: "ajax/alistar.eliminar.ajax.php",
        url: "api/alistar/eliminaritem",
        data: { "iditem": iditem, "req": req },
        dataType: "JSON",
        success: function (res) {

            if (res != false) {

                $(`#${iditem}`).remove();
            } else {
                var toastHTML = '<p class="truncate">No se pudo eliminar el item</span></p>';
                M.toast({ html: toastHTML, classes: "red darken-4" });
            }
        }
    });

}

// FUNCION QUE RECARGA LAS TABLAS
function recargarItems() {

    // se recarga tablas y ubicacion
    let ubicacion = $('#ubicacion').val();
    $.when(mostrarItems()).done(function () {
        $.when(mostrarCaja()).done(function () {
            $('#ubicacion').val(ubicacion);
            cambiarUbicacion();
        });
    });
}

//FUNCION QUE AGREGA ITEM A LA TABLA EDITABLE
function agregarItem(res) {

    //busca el estado de del resultado
    //si encontro el codigo de barras muestar el contenido de la busqueda
    if (res['estado'] == 'encontrado') {

        var items = res['contenido'];

        if (items) {
            swal(`${items['descripcion']}`, `disponibilidad: ${items['disponibilidad']}\t pedidos: ${items['pedidos']} `, {
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Cantidad a alistar",
                        type: "number",
                    },
                },
            })
                .then((value) => {
                    
                    // consigue el valor maximo en decenas que puede valer la cantidad de alistados
                    // EJ: entre 0 y 10 maximo valor=100, entre 11 y 100 maximo valor 1000
                    let a = 0;
                    let pot = items['pedidos'];

                    do {
                        a++;
                        pot = pot / 10
                    } while (pot > 1);

                    let maxvalue = Math.pow(10, a)

                    let colorwarning='';
                    let titlewarning='';
                    if (!value) {

                        value = 1;

                    }else if (value > maxvalue * 10) {

                        var toastHTML = `<p class="truncate black-text"><i class="fas fa-exclamation-circle"></i> Revisar cantidad alistada</span></p>`;
                        M.toast({
                            html: toastHTML, classes: "red lighten-2'",
                        });
                        colorwarning='red lighten-2';
                        titlewarning='Revisar cantidad alistada'

                    } else if (value > maxvalue) {

                        var toastHTML = `<p class='truncate black-text'><i class='fas fa-exclamation-circle'></i> Revisar cantidad alistada</span></p>`;
                        M.toast({
                            html: toastHTML, classes: 'yellow lighten-2',
                        });
                        colorwarning='yellow lighten-2';
                        titlewarning='Revisar cantidad alistada'
                    }

                    //   se guarda el id del item en el id de la fila
                    $('#tablaeditable').append($(`<tr
                                                   id='${items['iditem']}'
                                                   class='${colorwarning}'
                                                   title='${titlewarning}'
                                                  >
                                                    <td>${items['descripcion']}</td>
                                                    <td>${items['pedidos']}</td>
                                                    <td><input type= 'number' min='1' class='alistados eliminaritem' min=1 max=${maxvalue*10} required value='${value}'>  </td>
                                                    <td><button  title='Eliminar Item' class='btn-floating btn-small waves-effect waves-light red darken-3 ' > 
                                                        <i class='fas fa-times'></i>" 
                                                    </button></tr></td>
                                                  </tr>`));

                    // se muestra un mensaje con el item agregado
                    var toastHTML = `<p class='truncate'>Agregado Item <span class='yellow-text'>${items['descripcion']}</span></p>`;
                    M.toast({ html: toastHTML, classes: 'light-green darken-4 rounded', displayLength: 500 });
                    $('#codbarras').focus();
                });

            $('#TablaE').removeClass('hide');
        } else {
            swal('Item ya fue alistado en otra caja', {
                icon: 'warning',
            }).then((value) => {
                $('#codbarras').focus();
            });

        }
        //si no encontro el item regresa el contenido del error(razon por la que no lo encontro)
    } else {
        swal(res['contenido'], {
            icon: 'warning',
        }).then((value) => {
            $('#codbarras').focus();
        });
    }

}

// FUNCION QUE PONE LOS ITEMS  EN LA TABLA
function mostrarItems() {

    //consigue el numero de requerido
    var requeridos = $('.requeridos').val();
    //id usuario es obtenida de las variables de sesion
    var req = [requeridos, id_usuario];

    return $.ajax({

        url: 'api/alistar/items',//url de la funcion
        method: 'GET',  
        data: { 'req': req },
        dataType: 'JSON',
        success: function (res) {
            
            //si encuentra el item mostrarlo en la tabla
            if (res['estado'] != 'error') {


                let items = res['contenido'];

                $('#tablavista').html('');


                for (let i in items) {

                    // se guarda el id del item en el id de la fila
                    $('#tablavista').append($(`<tr id='${items[i]['iditem']}'>
                                            <td>${items[i]['descripcion']}</td>
                                            <td>${items[i]['disponibilidad']}</td>
                                            <td>${items[i]['pedidos']}</td>
                                            <td>${items[i]['ubicacion']}</td>
                                        </tr>`));

                }

                // se carga el menu seleccion de ubicaciones
                let ubicaciones = res['ubicaciones'];
                $('#ubicacion').html('');
                $('#ubicacion').append($(`<option value=''  selected>Ubicacion</option>`));
                for (let i in ubicaciones) {

                    $('#ubicacion').append($(`<option value="${ubicaciones[i]}"> ${ubicaciones[i]}</option>`));

                }
                $('.entradas').removeClass('hide');

            } else {
                //oculta las entradas
                $('.entradas').addClass('hide');
            }

        }

    });

}

// FUNCION QUE CREA O MUESTRA UNA CAJA
function mostrarCaja() {

    //consigue el numero de requerido
    var requeridos = $('.requeridos').val();
    //id usuario es obtenida de las variables de sesion
    var req = [requeridos, id_usuario];

    return $.ajax({

        // url: 'ajax/alistar.cajas.ajax.php',
        url: 'api/alistar/cajas',
        method: 'POST',
        data: { 'req': req },
        dataType: 'JSON',
        success: function (res) {

            // si la caja ya esta creada muestra los items en la tabla de alistar
            if (res['estadocaja'] == 'yacreada') {
                //si encontro el codigo de barras muestar el contenido de la busqueda
                if (res['estado'] == 'encontrado') {

                    //refresca las tablas, para volver a cargar los datos
                    $('#tablaeditable').html('');

                    var items = res['contenido'];
                    let maxvalue;

                    for (var i in items) {
                        // consigue el valor maximo en decenas que puede valer la cantidad de alistados
                        // EJ: entre 0 y 10 maximo valor=100, entre 11 y 100 maximo valor 1000
                        let a = 0;
                        let pot = items[i]['pedidos'];
                        do {
                            a++;
                            pot = pot / 10
                        } while (pot > 1);
                        maxvalue = Math.pow(10, a) * 10

                        // se guerda el id del item en el id de la fila 
                        $('#tablaeditable').append($(`<tr id='${items[i]['iditem']}'>
                                            <td>${items[i]['descripcion']}</td>
                                            <td>${items[i]['pedidos']}</td>
                                            <td><input type= 'number' min='1' class='alistados eliminaritem' min=1 max=${maxvalue} required value='1'></td>
                                            <td><button  title='Eliminar Item' class='btn-floating btn-small waves-effect waves-light red darken-3 ' > 
                                            <i class='fas fa-times'></i>
                                            </button></tr></td>
                                        </tr>`));

                    }

                    $('#TablaE').removeClass('hide');
                    // si hay una caja sin cerrar en otra requisicion muestra mensaje adventencia y recarga la pagina          
                } else if (res['estado'] == 'error2') {
                    swal({
                        title: '!No se puede generar caja¡',
                        text: `Caja sin cerrar en la requisicion ${res['contenido']}`,
                        icon: 'warning',
                    })
                        .then((ok) => {
                            location.reload();
                        });
                }

            }

        }

    });
}

function cambiarUbicacion() {
    let ubicacion = $('#ubicacion').val(); //dato de ubicacion del menu de seleccion 

    $('#TablaVi').removeClass('hide');
    // evita que alistadores vean todos los items
    if (perfil == 3 && (ubicacion == '' || !ubicacion)) {
        ubicacion = '---';
    }

    var filter, table, tr, td, i;

    filter = ubicacion.toUpperCase();
    table = document.getElementById('tablavista');
    tr = table.getElementsByTagName('tr');
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName('td')[3];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }

}