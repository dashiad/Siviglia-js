//Código para Datables

//Para inicializar datatables de la manera más simple
// $(document).ready( function () {
//     $('#dataTableJobs').DataTable();
// } );

// DataTable configurado
$(document).ready(function() {    
    $('#dataTableJobs').DataTable({
    //para cambiar el lenguaje a español
        "language": {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando trabajos del _START_ al _END_ de un total de _TOTAL_.",
                "infoEmpty": "Mostrando trabajos del 0 al 0 de un total de 0.",
                "infoFiltered": "(filtrado de un total de _MAX_.)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast":"Último",
                    "sNext":"Siguiente",
                    "sPrevious": "Anterior"
			     },
			     "sProcessing":"Procesando...",
            }
    });     
});