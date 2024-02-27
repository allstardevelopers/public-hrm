/*
 Template Name: Veltrix - Responsive Bootstrap 4 Admin Dashboard
 Author: Themesbrand
 File: Datatable js
 */

$(document).ready(function() {
    $('#datatable').DataTable({
        // "columnDefs": [ { 'type': 'date', 'targets': [0] } ],
        // "order": [[ 0, "desc" ]], //or asc
        columnDefs: [
            { type: 'date-eu', targets: 0 },
          ],
          order: [[ 0, 'desc' ]]
    });

    //Buttons examples
    var table = $('#datatable-buttons').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'colvis'],
        // "ordering": false
        columnDefs: [
            { type: 'date-eu', targets: 0 },
            { type: 'time', targets: 3 }
          ],
          order: [[ 0, 'desc' ], [ 3, 'desc' ]]
    });

    // Buttons examples
    var todaytable = $('#today-Datatable').DataTable({
        lengthChange: false,
        bPaginate: false,
        buttons: ['copy', 'excel', 'pdf', 'colvis'],
        columnDefs: [
            { type: 'number', targets: 0 },
            { type: 'time', targets: 3 },
            ],
            order: [[ 0, 'desc' ], [ 3, 'desc' ]]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    
    todaytable.buttons().container()
    .appendTo('#today-Datatable_wrapper .col-md-6:eq(0)');
} );