$(document).ready(function(){

  // sortable ui.
  $('table tbody').sortable({
    axis: 'y',
    items: "> tr.sortable",
    handle: ">td>i.fa-reorder",
    helper: function(e, tr){
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index)
      {
      // Set helper cell sizes to match the original sizes
        $(this).width($originals.eq(index).outerWidth());
        $(this).css('background-color', "white");
      });
        return $helper;
    },
    stop: function(event, ui){
      var order = new Array();
      $("table tbody tr.sortable").each(function(index){
        $(this).attr('data-template-ordre', index+1);
        order.push({
          id: $(this).attr('data-template-id'),
          ordre: index+1
        });
      });

      $.ajax({
        "url": $.trim($('#save-order').text()),
        "type": "POST",
        "data": JSON.stringify(order),
        success: function(msg){
          $('#alert-success').message({delay: 2});
          console.log(msg);
        },
        error: function(msg){
          $('#alert-error').message();
          console.log(msg);
        }
      });

    },
  });
});
