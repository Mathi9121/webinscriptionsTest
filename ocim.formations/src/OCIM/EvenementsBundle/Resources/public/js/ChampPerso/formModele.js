$(document).ready(function(){

	var place = $('#place');
	var modele = $('.forms-inline-list').attr('data-prototype');
	setOrdreUl();
	$('#place').sortable({
		handle : '.ui-handle',
		stop: setOrdreUl,
		axis: 'y',
	});

	$('a.ajoutPrototype').on('click', function(e){
		e.preventDefault();

		if(place.children('ul').length == 0)
		{
			var form = modele.replace(/__name__/g, 0);
		}
		else
		{
			var form = modele.replace(/__name__/g, place.children('ul').length);
		}
		place.append(form);
		addTagFormDeleteLink(place.find('ul').last().find('li').last());
		setOrdreUl();
		//datepicker
		$("input[data-tool='datepicker']").datetimepicker({
			lang:'fr',
			timepicker:false,
			format:'d/m/Y',
			minDate: false,
			maxDate: false,
			scrollInput: false,
			i18n:{
				fr:{
					months:[
					'Janvier','Fevrier','Mars','Avril',
					'Mai','Juin','Juillet','Août',
					'Septembre','Octobre','Novembre','Décembre',
					],
					dayOfWeek:[
					"Di", "Lu", "Ma", "Me",
					"Je", "Ve", "Sa",
					]
				}
			},
		});

	});

	$("form #place ul").each(function() {
        addTagFormDeleteLink($(this).find('li').last());
    });

	function addTagFormDeleteLink($endroit) {
		var $removeFormA = $('<a href="#" class="btn btn-smaller btn-red btn-outline"><i class="fa fa-times"></i></a>');
		$endroit.append($removeFormA);

		$removeFormA.on('click', function(e) {
			e.preventDefault();
			$endroit.parent().remove();
			});
	}

	function setOrdreUl(){
		$.each($('#place ul'), function(i, n){
			$(this).find('li').first().html("<i class='fa fa-arrows'></i>");
			$(this).find('input.ordreModeles').val(i);
		})
	}



});
