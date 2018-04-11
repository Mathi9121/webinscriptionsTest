$(document).ready(function(){

//input date
$('input.datepicker').wrap('<div class="input-groups"></div>').before('<span class="input-prepend"><i class="fa fa-calendar"></i></span>');

var place = $('#ocim_evenementsbundle_evenement_evenementFormule');
var modele = place.attr('data-prototype');
var nouvellesOptions = "";


	$('a.ajouterFormule').on('click', function(e){
		var form = modele.replace(/__name__/g, place.children().length);
		form = $(form);
		form.find('select').prepend(nouvellesOptions);
		place.append(form);
		addTagFormDeleteLink(place.find('select').last());
	});

	place.find('select').each(function() {
        addTagFormDeleteLink($(this));
    });

	function addTagFormDeleteLink($endroit) {
    var $removeFormA = $('<span class="input-append"><a href="#" class="" title="Supprimer cette formule"><i class="fa fa-times"></i></a></span>');
    $endroit.after($removeFormA);
	$endroit.parent().addClass('input-groups');

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $endroit.parent().remove();
		});
	}


// Ajout dun type de evenement en ajax
$("#ajouttype").click(function(e){
	e.preventDefault();
	var type = prompt("Entrez un nouveau type de evenement");
	if(type){
		$.ajax({
			type: 'POST',
			url : $.trim($("#ajouttype").attr('data-url')),
			data: JSON.stringify(type),
		}).done(function(msg){
			$("#ocim_evenementsbundle_evenement_type").append("<option value='"+msg+"'>"+type+"</option>");
			$("#ocim_evenementsbundle_evenement_type").val(msg);
		}).fail(function(msg){
			console.log(msg);
		});
	}

});

$('#nouvelleFormule').click(function(e){
	e.preventDefault();

	var popup = $("<div class='white-popup'><h2>Nouvelle Formule</h2><form class='forms'></form></div>");
	popup.find('form.forms').html('<div class="units-row end"><div class="unit-100"><label>Description<textarea class="width-100 form-description"></textarea></div></div>');
	popup.find('form.forms').append('<div class="units-row end"><div class="unit-50"><label>Tarif<input type="text" class="width-100 form-tarif"/></div><div class="unit-50"><label><input type="checkbox" class="form-check" value="herbergement"/>Hébergement</label><label><input type="checkbox" class="form-check" value="midi"/>Midi</label><label><input type="checkbox" class="form-check" value="soir"/>Soir</label></div></div><div class="group"><a href="#" class="btn left">Annuler</a><input class="btn btn-green right" type="submit" value="Enregistrer"/></div>');

	//btn close de la popup
	popup.find('.btn.left').click(function(e){
		e.preventDefault();
		$.magnificPopup.close();
	});

	//btn enregistrer de la popup
	popup.find('form').submit(function(e){
		e.preventDefault();
		var data = new Array({
			'description' : $(this).find('.form-description').val(),
			'tarif' : $(this).find('.form-tarif').val(),
			'hebergement' : ($(this).find('.form-check').eq(0).prop( "checked"))? 1 : 0,
			'midi' : ($(this).find('.form-check').eq(1).prop( "checked"))? 1 : 0,
			'soir' : ($(this).find('.form-check').eq(2).prop( "checked"))? 1 : 0,
		});
		$.ajax({
			url: $.trim($('#nouvelleFormule').attr('data-url')),
			type: 'post',
			data: JSON.stringify(data),
		}).done(function(msg){
			nouvellesOptions +=  "<option value='"+msg+"'>"+data['0'].tarif+((!isNaN(data['0'].tarif))? "€": "")+" | "+ data['0'].description+"</option>";
			var form = modele.replace(/__name__/g, place.children().length);
			form = $(form);
			form.find('select').prepend(nouvellesOptions);
			form.find('select').val(msg);
			place.append(form);
			addTagFormDeleteLink(place.find('select').last());
			$.magnificPopup.close();
		});

	});

	$.magnificPopup.open({
		items: {
			src: popup,
			type: 'inline',
			closeBtnInside: true
		}
	});

})


});
