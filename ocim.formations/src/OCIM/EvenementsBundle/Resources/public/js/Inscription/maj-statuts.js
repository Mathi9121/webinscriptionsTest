$(document).ready(function(){

  // init les compteurs /comptes
  compteurs();

  //event listener
  $("a.majstatuts").on('click',function(e){
    e.preventDefault();

    var idinscription = $(this).attr('data-idinscription');
    var statutinscription = $(this).attr('data-statut');
    var statutfinancement = $(this).attr('data-statutfinancement');

    var statutconvention = $(this).attr('data-statutconvention');
    statutconvention = (statutconvention == "")? "null" : statutconvention;

    var popup = $('<div class="white-popup"><h2>Statut de l&apos;inscription</h2><hr/><p class="text-centered choix inscription"><span class="annule" data-statut="3"><i class="fa fa-times fa-fw"></i>Annulé</span><span class="attente" data-statut="2"><i class="fa fa-question fa-fw"></i>En attente</span><span class="valide" data-statut="1"><i class="fa fa-check fa-fw"></i>Validé</span></p></div>');
    popup.find('[data-statut='+statutinscription+']').addClass('selected');

    popup.append('<br/><h2>Statut de la Convention</h2><hr/>');
    popup.append('<p class="text-centered choix convention"><span class="non" data-statutconvention="0"><i class="fa fa-times fa-fw"></i>Non</span><span class="saispas" data-statutconvention="null"><i class="fa fa-question fa-fw"></i>Ne sais pas</span><span class="oui" data-statutconvention="1"><i class="fa fa-check fa-fw"></i>Confirmé</span></p>');

    popup.append('<br/><h2>Statut du Financement</h2><hr/>');
    popup.append('<p class="text-centered choix financement"><span class="non" data-statutfinancement="0"><i class="fa fa-times fa-fw"></i>Non</span><span class="saispas" data-statutfinancement="null"><i class="fa fa-question fa-fw"></i>Ne sais pas</span><span class="oui" data-statutfinancement="1"><i class="fa fa-check fa-fw"></i>Confirmé</span></p>');
    popup.find('[data-statutfinancement='+statutfinancement+']').addClass('selected');

    popup.find('[data-statutconvention='+statutconvention+']').addClass('selected');


    popup.find('.choix.inscription span').on('click', function(){
      popup.find('.choix.inscription span').removeClass('selected');
      $(this).addClass('selected');
      var nouveauStatut = $(this).attr('data-statut');
      var data = new Array({'idinscription': idinscription, 'statut': nouveauStatut});

      changeStatutInscription(data);
    });

    popup.find('.choix.financement span').on('click', function(){
      popup.find('.choix.financement span').removeClass('selected');
      $(this).addClass('selected');
      var nouveauStatut = $(this).attr('data-statutfinancement');
      console.log(nouveauStatut);
      var data = new Array({'idinscription': idinscription, 'statut': nouveauStatut});

      changeStatutFinancement(data);
    });

    popup.find('.choix.convention span').on('click', function(){
      popup.find('.choix.convention span').removeClass('selected');
      $(this).addClass('selected');
      var nouveauStatut = $(this).attr('data-statutconvention');
      var data = new Array({'idinscription': idinscription, 'statut': nouveauStatut});
      changeStatutConvention(data);
    });

    $.magnificPopup.open({
      items: {
        src: popup,
        type: 'inline',
      },
      callbacks: {
        close: function() {
          window.location.reload();
        }
      }
    });

  });

});

//fonction qui initialise et rafraichi les compteurs sur les inscriptions. appelée au chargement de la page et changement d'états
function compteurs(){

  //statuts
  var Sattente  = $("#general tr.attente").length;
  var Svalide  = $("#general tr.valide").length;
  var Sannule  = $("#general tr.annule").length;
  $('#comptes li.statuts div.attente p').first().text(Sattente);
  $('#comptes li.statuts div.valide p').first().text(Svalide);
  $('#comptes li.statuts div.annule p').first().text(Sannule);

  //conventions
  var Cattente  = $("#general tr.statut.valide .tdfinancement span.label-yellow").length;
  var Cvalide  = $("#general tr.statut.valide .tdfinancement span.label-green").length;
  var Cannule  = $("#general tr.statut.valide .tdfinancement span.label-red").length;
  $('#comptes li.conventions div.attente p').first().text(Cattente);
  $('#comptes li.conventions div.valide p').first().text(Cvalide);
  $('#comptes li.conventions div.annule p').first().text(Cannule);

  //financement
  var Fattente  = $("#financement tr.statut.valide span.label-yellow").length;
  var Fvalide  = $("#financement tr.statut.valide span.label-green").length;
  var Fannule  = $("#financement tr.statut.valide span.label-red").length;
  $('#comptes li.financement div.attente p').first().text(Fattente);
  $('#comptes li.financement div.valide p').first().text(Fvalide);
  $('#comptes li.financement div.annule p').first().text(Fannule);

  //totaux
  var users  = $("#general tbody tr").length;
  var intervs  = $("#intervenants tbody tr").length;
  var pcst  = $("#general tbody tr td i.fa.fa-flask").length;
  $('#comptes li.totaux div.users p span').first().text(users);
  $('#comptes li.totaux div.intervs p span').first().text(intervs);
  $('#comptes li.totaux div.pcst p span').first().text(pcst);

  //formules
  $("#comptes li.formule div>p").each(function(){
    var id = $(this).attr('data-idformule');
    var elem = $("<p></p>").text($('#general table tbody tr.statut.valide td.inscformule').filter("[data-idformule="+id+"]").length).hide();
    $(this).parent().append(elem);
  });
  $("#comptes li div").hover(
    function(){
      $(this).children('p').slideToggle('fast');
    },
    function(){
      $(this).children('p').slideToggle("fast");
    }
  );
}

function changeStatutInscription(data){
  $.ajax({
    url: $.trim($('#updateinscription').text()),
    type: "POST",
    data: JSON.stringify(data)
  }).done(function(statut){
    var classStatut = '';

    if(statut == 1){
      classStatut = "valide";
    }
    else if(statut == 2){
      classStatut = "attente";
    }else if(statut == 3){
      classStatut = "annule";
    }

    $("tr[data-idinscription="+data['0']['idinscription']+"]").removeClass('attente').removeClass('valide').removeClass('annule').addClass(classStatut);

    }).fail(function(){
      alert("Erreur pendant l'enregistrement ! Rechargez la page.");
    });

}

function changeStatutFinancement(data){
  $.ajax({
    url: $.trim($('#updatefinancement').text()),
    type: "POST",
    data: JSON.stringify(data)
  }).done(function(statut){
    console.log("reponse :" + statut);
    }).fail(function(){
      alert("Erreur pendant l'enregistrement ! Rechargez la page.");
    });

}

function changeStatutConvention(data){
  $.ajax({
    url: $.trim($('#updateconvention').text()),
    type: "POST",
    data: JSON.stringify(data)
  }).done(function(statut){
    console.log(statut);

    var statutsConvention = new Array({'1' : '<span class="label label-outline label-green" title="Demande de convention confirmée"><i class="fa fa-check"></i></span>', '0' : '<span class="label label-outline label-red" title="Non"><i class="fa fa-times"></i></span>', 'null': '<span class="label label-outline label-yellow" title="Ne sais pas"><i class="fa fa-question"></i></span>'});

    $("#general tr[data-idinscription="+data['0']['idinscription']+"] .tdconvention").html(statutsConvention['0'][statut]);

  }).fail(function(){
    alert("Erreur pendant l'enregistrement ! Rechargez la page.");
  });

}
