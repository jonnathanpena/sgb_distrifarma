var $pagination = $('#pagination'),


    totalRecords = 0,


    records = [],


    displayRecords = [],


    recPerPage = 10,

function generate_table() {
    $('#resultados .table-responsive table tbody').empty();
    $.each(displayRecords, function(index, row) {
        var tr;
        tr = $('<tr/>');
        tr.append("<td>" + row.df_codigo_prod + "</td>");
        tr.append("<td class='text-center'>" + row.df_cant_bodega + "</td>");
        //tr.append("<td class='text-center'>" + row.df_cant_transito + "</td>");
        tr.append("<td class='text-center'>" + row.df_nombre_producto + "</td>");
        tr.append("<td class='text-center'>" + row.df_ppp_ind + "</td>");
        tr.append("<td class='text-center'>" + row.df_pvt_ind + "</td>");
        tr.append("<td class='text-center'>" + (row.cantidad * row.df_ppp_ind).toFixed(2) + "</td>");
        tr.append("<td class='text-center'>" + (row.cantidad * row.df_pvt_ind).toFixed(2) + "</td>");    
        //tr.append("<td class='text-center'>" + row.df_minimo_sug + "</td>");    
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    })
}