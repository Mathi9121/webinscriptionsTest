$(document).ready(function(){
  $('.btn-del').on('click', function(e){
    e.preventDefault();
    var confirm = window.confirm("Etes-vous sûr de vouloir supprimer cette entité ?");
    if(confirm){
      window.location=$(this).attr('href');
    }
  });

  $('.btn-copy').on('click', function(e){
    e.preventDefault();
    var nom = window.prompt('Nouveau nom', $(this).attr('data-nom')+" - copie");

    if (nom != null){
      var data = {'nom': nom, 'id': $(this).attr('data-id')};
      $.ajax({
        type: "POST",
        url: $(this).attr('data-href'),
        //dataType: 'json',
        data: JSON.stringify(data),
      })
      .done(function( data ) {
        location.reload();
      });
    }

  });
});
