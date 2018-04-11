$(document).ready(function(){

	// initialisation
	$('td.insc.actions').hide();

	//quand on change d'onglet, on ferme les détails s'il y en a
	$('.nav-tabs').on('show.tools.tabs', function(tab, hash)
	{
		removeBox();
	});




	//ecouteur devenement : clique sur une ligne, sauf premiere case
	$('.tab:not(#champPerso, #intervenants) table tr td').not(':first-of-type').css('cursor', 'pointer').click(function(){

		// la ligne est selectionnée
		$(this).closest('tr').addClass("selected", 500, "easeInOutCubic");

		// données pour la boite
		var idinscription = $(this).closest('tr').attr('data-idinscription');
		var nom = $(this).closest('tr').find('.nom-stagiaire').text();
		var prenom = $(this).closest('tr').find('.prenom-stagiaire').text();
		var formule = $("#general table tbody tr[data-idinscription='"+idinscription+"']").find("td.inscformule abbr").attr('title');
		var liens = $("#general table tbody tr[data-idinscription='"+idinscription+"']").children().last().find('ul').clone(true);
		$(liens).removeClass('dropdown');
		$(liens).show();

		var trPosition =  $(this).offset().top - $(this).closest('.tab').offset().top;
		var tableH = $(this).closest('table').height();

		var dateInscription = $("#general table tbody tr[data-idinscription='"+idinscription+"']").find('td.dateinscription').text();
		var mois = [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
		var dateJ = dateInscription.substr(0,2);
		var dateM = dateInscription.substr(3,2);
		var dateA = dateInscription.substr(6,4);
		dateInscription = dateJ + " " + mois[dateM - 1] + " " + dateA;

		var inscription = {
			id : idinscription,
			nom : nom,
			prenom : prenom,
			formule : formule,
			liens : liens,
			trPosition : trPosition,
			tableH: tableH,
			date : dateInscription,
		}

		// fermeture des détails si la meme ligne est cliquée
		if( $(this).closest('tr').is('.selected') ){
			removeBox();
		}

		else if($(this).closest('.tab').is(".unit-80")){
			$("tr").not($(this).closest('tr')).removeClass('selected');
			$(".details-inscription").remove();
			createBox(inscription, this);
		}

		// on affiche les détails d'une inscription
		else {
			$(this).closest('.tab').switchClass('unit-100', "unit-80", 300, "easeInOutCubic", function(){
				createBox(inscription, this);
			});
		}

	});

	function createBox(data, place){
		//animation finie
				//on crée la boite

				var details = $("<div class='unit-20 details-inscription'><a href='#' class='right close-box'><i class='fa fa-times'></i></a></div>");
				$("<h2>"+ data.prenom +" "+ data.nom +"</h2><hr/><p class='text-centered'>Inscription le <strong>"+ data.date +"</strong><br/><br/><span class='label' >"+ data.formule +"</span></p>").appendTo(details);
				$(data.liens).appendTo(details);
				$(data.liens).wrap('<nav class="nav nav-stacked"></nav>');


				// ajout
				$(place).closest('.tab').after(details);

				// position de la boite
				$(details).css("margin-top", Math.min(data.trPosition, Math.abs(data.tableH - $(details).height())));

				//evenement fermeture
				$('.details-inscription a.close-box').click(function(e){
					e.preventDefault();
					removeBox();
				});
	}

	function removeBox(){

		$(".details-inscription").remove();
		$('.tab').switchClass('unit-80', "unit-100", 300, "easeInOutCubic");
		$('tr').removeClass('selected');
	}
});
