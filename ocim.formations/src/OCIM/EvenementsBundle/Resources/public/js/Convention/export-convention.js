$(document).ready(function(){

  //ecouteur
  $(".export-convention").on("click", function(e){
    e.preventDefault();
    var idinscription = $(this).parents('tr').attr('data-idinscription');
    console.log(idinscription);
    $.ajax({
      type: 'POST',
      url: $.trim($("#href-liensexport").attr('data-href')),
      data: idinscription,
    }).done(function(data){
      var rep = JSON.parse(data);

      // construction du select html
      var select = $('<select class="width-100"></select>');
      for(var i = 0; i < rep.length; i++){
        select.append($('<option value="'+rep[i]._url+'" data-preview="'+rep[i]._preview+'">'+rep[i]._nom+'</option>'));
      }

      var popupExport = $('<div class="white-popup"><h2>Export d&apos;un document</h2><hr/></div>');
      popupExport.append(select);
      popupExport.append("<br/><br/><div class='group'><a class='btn left' href="+popupExport.find('select option:selected').attr('data-preview')+"><i class='fa fa-eye fa-fw'></i>Prévisualiser</a><a href='"+popupExport.find('select').val()+"' class='btn btn-green right lienexport' target='blank'><i class='fa fa-download fa-fw'></i>Exporter</a></div>");

      $.magnificPopup.open({
        items: {
          src: popupExport,
          type: 'inline',
        },
        callbacks:{
          open: function() {
            $('.white-popup').find('select').on('change', function(){
              $('.white-popup').find('a.lienexport').attr('href', $(this).val());
              $('.white-popup').find('a.left').attr('href', $(this).find('option:selected').attr('data-preview'));
            });
          },
        }
      });

    }).fail(function(msg){
      console.log(msg);
    });



  })

})
