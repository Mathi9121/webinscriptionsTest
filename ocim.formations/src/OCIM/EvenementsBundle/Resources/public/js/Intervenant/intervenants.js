$(document).ready(function(){

  $('#datatable tbody tr').on('click', function(e){

    $(this).toggleClass('checked');

    var chkbox = $(this).find('td:eq(0) label input');
    chkbox.prop("checked", !chkbox.is(':checked'));

  });

});
