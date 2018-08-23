$(document).ready(function () {

/* ============================================================================================================================
                                                    INICIALIZACION   
============================================================================================================================*/
    // INICIA DATATABLE

    // table = iniciar_tabla();
    
    
    // INICIAR TABS
    $('.tabs').tabs({ 'swipeable': false });
    
    // // pone requisiciones en el input select
    $.ajax({
        url: "ajax/alistar.requisicion.ajax.php",
        method: "POST",
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

        //destruye datatabel para reiniciarla
        //espera a que la funcion termine para reiniciar las tablas

        
        $.when(mostrarItems()).done(function () {
            
            $.when( mostrarCaja()).done(function () {
                $(".input_barras").removeClass("hide");
                // // $('.tabs').tabs({ 'swipeable': true });//reinicia el tabs
                
                
                // var ubicaciones=table.columns(7).data().eq(0).sort().unique().toArray();
                
                // for (let i in ubicaciones) {
                    
                //     $("#ubicacion").append($('<option value="' + ubicaciones[i]+ '">' + ubicaciones[i]+ '</option>'));

                // }
                // //no muestra datos en la tabla
                // $('#ubicacion').val("");
                // cambiarUbicacion();

            });   
        });

    });

    // // EVENTO INPUT  CODIGO DE BARRAS
    // $("#codbarras").keypress(function (e) {

    //     //si se presiona enter busca el item y lo pone en la pagina
    //     if (e.which == 13) {
            
    //         BuscarCodBar();
    //         mostrarItems();
    //         cambiarUbicacion();
    //     }

    // });


    // // EVENTO SI SE PRESIONA EL BOTON DE AGREGAR CODIGO DE BARRAS(+)
    // $("#agregar").click(function (e) {

    //     BuscarCodBar();
    //     mostrarItems();
    //     cambiarUbicacion();

    // });

    // // EVENTO CUANDO SE MODIFICA UNA CELDA DE LA TABLA
    // $('#tablaeditable').on('change', 'td', function () {

    //     var dt = $.fn.dataTable.tables()[1];
    //     var tabla = $(dt).DataTable();

    //     //se obtiene el valor de la variable y se le asigna a datatable para que quede guardado
    //     celda = table.cell(this);

    //     var nuevovalor = $(this).find("input").val();
    //     var fila = table.row(this)

    //     // si la tabla es responsive
    //     if (fila.data() == undefined) {

    //         var fila = $(this).parents('tr');
    //         if (fila.hasClass('child')) {
    //             fila = fila.prev();
    //         }
    //         tabla.row(fila).cell(fila, 1).data("<input  type= 'number' min='0' class='alistados'  value='" + nuevovalor + "'></input>");

    //     } else {

    //         tabla.cell(this).data("<input  type= 'number' min='0' class='alistados'  value='" + nuevovalor + "'></input>");
    //     }

    // });

    // // EVENTO CUANDO SE ESCRIBE EN EL INPUT DE LA TABLA EDITABLE(EVITA QUE SE DIGITEN LETRAS)
    // $('#tablaeditable').on('keydown', 'input', function (e) {

    //     // permite: spacio, eliminar , tab, escape, enter y  .
    //     if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
    //         // permite: Ctrl+letra, Command+letra
    //         ((e.keyCode >= 0) && (e.ctrlKey === true || e.metaKey === true)) ||
    //         // permite: home, fin, izquierda, derecha, abajo, arriba
    //         (e.keyCode >= 35 && e.keyCode <= 40)) {
    //         // no hace nada si cumple la condicion
    //         return;
    //     }
    //     // solo acepta numeros
    //     if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
    //         //previene mandar los datos al input
    //         e.preventDefault();
    //     }
    // });

    // // EVENTO SI SE PRESIONA 1 BOTON EN LA TABLA EDITABLE(ELIMINAR ITEM)
    // $('#tablaeditable').on('click', 'button', function (e) {
    //     //consigue el numero de requerido
        
    //     var requeridos = $(".requeridos").val();
    //     //id usuario es obtenida de las variables de sesion
    //     var req = [requeridos, id_usuario];
        
    //     var tabla = $("#TablaEd").DataTable();       

    //     celda = table.cell(this);

    //     var fila = table.row(this)
    //     //se otiene el valor del id del item donde esta el boton presionado 
    //     // si la tabla es responsive
    //     if (fila.data() == undefined) {

    //         fila = $(this).parents('tr');
    //         if (fila.hasClass('child')) {
    //             fila = fila.prev();
    //         }
    //     } else {
    //         fila=this;
    //     }
    //     let iditem = tabla.row(fila).cell(fila, 4).data();
    //     const nomitem = tabla.row(fila).cell(fila, 0).data();

    //     swal({
    //         title: `¿Quitar item ${nomitem}?`,
    //         icon: "warning",
    //         buttons: ['Cancelar', 'Quitar']
    //     })
    //     .then((Quitar) => {   

    //         if(Quitar) {

    //             $.ajax({
    //                 type: "POST",
    //                 url: "ajax/alistar.eliminar.ajax.php",
    //                 data: { "iditem": iditem,"req":req},
    //                 dataType: "JSON",
    //                 success: function (res) {
                        
    //                     if (res!=false) {
                            
    //                         tabla.row(fila).remove().draw('false');
    //                     }else{
    //                         var toastHTML = '<p class="truncate">No se pudo eliminar el item</span></p>';
    //                         M.toast({ html: toastHTML, classes: "red darken-4" });
    //                     }
    //                 }
    //             });

    //         }

    //     });
    // });

    // // EVENTO SI SE PRESIONA EL BOTON CERRAR
    // $("#cerrar").on('click', function (e) {


    //     //consigue el numero de requerido
    //     var requeridos = $(".requeridos").val();
    //     //id usuario es obtenida de las variables de sesion
    //     var req = [requeridos, id_usuario];

    //     //si se presiona aceptar se continua con el proceso
    //     swal({
    //         title: "¿Cerrar caja?",
    //         icon: "warning",
    //         buttons: ['Cancelar', 'Cerrar']
    //     })
    //         .then((Cerrar) => {

    //             //si se le da click en cerrar procede a pasar los items a la caja y a cerrarla
    //             if (Cerrar) {

    //                 var datos = $("#TablaEd").DataTable().data().toArray();

                    
    //                 var items = new Array();
    //                 for (var i in datos) {

    //                     items[i] = {
    //                         "codigo": datos[i][3],
    //                         "alistados": $(datos[i][1]).val()
    //                     }

    //                 }
                    
    //                 //guarda el tipo de caja en una variable
    //                 var tipocaja = $("#caja").val();


    //                 $.ajax({
    //                     url: 'ajax/alistar.empacar.ajax.php',//url de la funcion
    //                     method: 'post',//metodo post para mandar datos
    //                     data: { 'req': req, "tipocaja": tipocaja, "items": items },//datos que se enviaran          
    //                     success: function (res) {
  
    //                         if (res) {

    //                             swal("¡Caja cerrada exitosamente!", {
    //                                 icon: "success",
    //                             })
    //                                 .then((event) => {

    //                                     location.reload(true);
                                        
    //                                 });

    //                         } else {

    //                             swal("¡Error al cerrar la caja!", {
    //                                 icon: "error",
    //                             });

    //                         }

    //                     }
    //                 });
    //             }
    //         });

    // });

    // // FUNCION QUE FILTRA ITEMS POR UBICACION
    // $("#ubicacion").change(function (e) { 

    //     cambiarUbicacion();

    // });

});



/* ============================================================================================================================
                                                FUNCIONES   
============================================================================================================================*/

// // FUNCION QUE BUSCA EL CODIGO DE BARRAS
// function BuscarCodBar() {

//     //consigue el codigo de barras
//     let codigo = $('#codbarras').val();
//     //consigue el numero de requerido
//     let requeridos = $(".requeridos").val();
//     //id usuario es obtenida de las variables de sesion
//     let req = [requeridos, id_usuario];
    
//     // ajax para ejecutar un script php mandando los datos
//     return $.ajax({
//         url: 'ajax/alistar.items.ajax.php',//url de la funcion
//         type: 'post',//metodo post para mandar datos
//         data: { "codigo": codigo, "req": req },//datos que se enviaran
//         dataType: 'json',
//         success: function (res) {
            
//             AgregarItem(res);

//             $('#codbarras').val("");
//         }

//     });


// }


// //FUNCION QUE AGREGA ITEM A LA TABLA EDITABLE
// function AgregarItem(res) {

//     //busca el estado de del resultado
//     //si encontro el codigo de barras muestar el contenido de la busqueda
//     if (res['estado'] == 'encontrado') {
//         var items = res['contenido'];

//         swal(`${items['descripcion']}` ,`disponibilidad: ${items['disponibilidad']}\t pedidos: ${items['pedidos']} `, {
//             content: {
//                 element: "input",
//                 attributes: {
//                   placeholder: "Cantidad a alistar",
//                   type: "number",
//                 },
//               },
//           })
//           .then((value) => {
//               if (!value) {
//                   value=1;
//               }
//             var tabla = $('#TablaEd').DataTable();
//             tabla.row.add( [
//                 items['descripcion'],  
//                 `<input type= 'number' min='1' class='alistados eliminaritem' value='${value}'>`,
//                 items['pedidos'],
//                 items['codigo'],
//                 items['iditem'],
//                 items['referencia'],
//                 items['disponibilidad'],    
//                 items['ubicacion'],  
//                 `<button  title='Eliminar Item' class='btn-floating btn-small waves-effect waves-light red darken-3 ' > 
//                 <i class='fas fa-times'></i>" 
//                 </button></tr>`,
//             ] ).draw(false);

//             $("#TablaE").removeClass("hide");

//             // se muestra un mensaje con el item agregado
//             var toastHTML = '<p class="truncate">Agregado Item <span class="yellow-text">' + items['descripcion'] + '</span></p>';
//             M.toast({ html: toastHTML, classes: "light-green darken-4 rounded",displayLength: 500 });
//         });
//         //si no encontro el item regresa el contenido del error(razon por la que no lo encontro)
//     } else {
//         swal(res['contenido'], {
//             icon: "warning",
//         })
//     }

// }


// // FUNCION QUE PONE LOS ITEMS  EN LA TABLA
function mostrarItems() {

    //consigue el numero de requerido
    var requeridos = $(".requeridos").val();
    //id usuario es obtenida de las variables de sesion
    var req = [requeridos, id_usuario];

    return $.ajax({

        url: "ajax/alistar.items.ajax.php",
        method: "POST",
        data: { "req": req },
        dataType: "JSON",
        success: function (res) {
            
            //si encuentra el item mostrarlo en la tabla
            if (res['estado'] != "error") {
                
                
                var items = res['contenido'];
                
                $('#tablavista').html("");

                for (var i in items) {   
                    
                    $('#tablavista').append($(`<tr>
                                            <td>${items[i]['descripcion']}</td>
                                            <td>${items[i]['disponibilidad']}</td>
                                            <td>${items[i]['pedidos']}</td>
                                            <td>${items[i]['ubicacion']}</td>
                                        </tr>`));

                }

            }

        }

    });

}


// // FUNCION QUE CREA O MUESTRA UNA CAJA
function mostrarCaja() {

    //consigue el numero de requerido
    var requeridos = $(".requeridos").val();
    //id usuario es obtenida de las variables de sesion
    var req = [requeridos, id_usuario];

    return $.ajax({

        url: "ajax/alistar.cajas.ajax.php",
        method: "POST",
        data: { "req": req },
        dataType: "JSON",
        success: function (res) {
            
            // si la caja ya esta creada muestra los items en la tabla de alistar
            if (res['estadocaja'] == 'yacreada') {
                //si encontro el codigo de barras muestar el contenido de la busqueda
                if (res['estado'] == 'encontrado') {
                    
                    //refresca las tablas, para volver a cargar los datos
                    

                    var items = res['contenido'];
                    
                    for (var i in items) {
                        
                        $('#tablaeditable').append($(`<tr>
                                            <td>${items[i]['descripcion']}</td>
                                            <td>${items[i]['alistados']}</td>
                                            <td><button  title='Eliminar Item' class='btn-floating btn-small waves-effect waves-light red darken-3 ' > 
                                            <i class='fas fa-times'></i>" 
                                            </button></tr></td>
                                        </tr>`));

                    }
                    // si hay una caja sin cerrar en otra requisicion muestra mensaje adventencia y recarga la pagina          
                } else if (res['estado'] == 'error2') {
                    swal({
                        title: "!No se puede generar caja¡",
                        text: "Caja sin cerrar en la requisicion "+res['contenido'],
                        icon: "warning",
                    })
                        .then((ok) => {
                            location.reload();
                        });
                }

            }

        }

    });
}

// function cambiarUbicacion(){
//     let ubicacion=$('#ubicacion').val(); //dato de ubicacion del menu de seleccion 
    
//     let tabla=$("#TablaVi").DataTable();

//     // evita que alistadores vean todos los items
//     if (perfil==3 &&  ubicacion=='') {
//         ubicacion='---';
//     }
//     // tabla.columns(7).search(ubicacion).draw();
//     // ubicacion="EB09";
//     tabla.columns(7).search(ubicacion).draw();
// }

// // FUNCION QUE INICIA DATATABLE
// function iniciar_tabla(tabla) {

//     if (!tabla) {
//         tabla="table.tablas";
//     }

//     var tabla = $(tabla).DataTable({

//         responsive: true,

//         "bLengthChange": false,
//         "bFilter": true,
//         "sDom": '<"top">t<"bottom"irp><"clear">',
//         "pageLength": 5,    
//         "columnDefs": [ {
//             "targets": 5,
//             "orderable": false
//         } ],
//         "language": {
//             "sProcessing": "Procesando...",
//             "sZeroRecords": "No se encontraron resultados",
//             "sEmptyTable": "Ningún dato disponible en esta tabla",
//             "sInfo": "_TOTAL_ Items",
//             "sInfoEmpty": "",
//             "sInfoFiltered": "(filtrado _MAX_ registros)",
//             "sSearch": "Buscar:",
//             "sUrl": "",
//             "sInfoThousands": ",",
//             "sLoadingRecords": "Cargando...",
//             "oPaginate": {
//                 "sFirst": "Primero",
//                 "sLast": "Último",
//                 "sNext": "Siguiente",
//                 "sPrevious": "Anterior"
//             },
//         },
//         paging: false,
//         order: [[7, 'asc']],
        
//         rowGroup: {
//             dataSrc: 7
//         },
//         scrollY:        300,
//         scrollCollapse: true,

//     });

//     return tabla;

// }