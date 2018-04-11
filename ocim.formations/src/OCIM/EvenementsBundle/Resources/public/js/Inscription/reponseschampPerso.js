$(document).ready(function(){

	initTotaux();

	// rend le contenu des cases de type text editable
	$('td.champPerso[data-type="text"]').attr('contenteditable', 'true');

	// on surveille le click sur les cases de type bool
	$('td.champPerso[data-type="bool"]').click(function(){

		// les variables utiles à lenregistrement ajax
		var type = $(this).attr('data-type');
		var reponse = $.trim($(this).attr('data-reponse'));
		var idmodele = $(this).attr('data-idmodele');
		var idpersonne = $(this).parent('tr').attr('data-idpersonne');
		var idreponse = $(this).attr('data-idreponse');

		// on met dans un tableau
		var data = new Array();
		data.push({
			'type' : type,
			'reponse' : reponse,
			'idmodele' : idmodele,
	    'idpersonne' : idpersonne,
	    'idreponse' : idreponse,
			});

		// on met un icone dans la case pour montrer le chargement
		$(this).html('<i class="fa fa-refresh fa-spin"></i>');
		// on envoit pour enregistrement
		enregistre(data, this);

	});

	$('td.champPerso[data-type="text"]').blur(function(){
		var data = new Array();
		data.push({
			'type' : $(this).attr('data-type'),
			'reponse' : $.trim($(this).text()),
			'idmodele' : $(this).attr('data-idmodele'),
			'idpersonne' : $(this).parent('tr').attr('data-idpersonne'),
			'idreponse' : $(this).attr('data-idreponse'),
		});

		enregistre(data, this);
	});
});



function enregistre(data, td){
	$.ajax({
		type: "POST",
		url: $('#majChampPerso').text(),
		data: JSON.stringify(data),
	})
	.done(function( msg ) {

		//message succes
		$('#message-save').message({'delay': 1});

		msg = JSON.parse(msg);
		$(td).attr('data-idreponse', msg[0].idreponse);


		console.log(msg[0].reponse);

		if(msg[0].type == 'bool'){
			if(msg[0].reponse == true){
				$(td).attr('data-reponse', 1);
				$(td).html('<span class="champPerso ok">1</span>');
			}
			else if(msg[0].reponse == false){
				$(td).attr('data-reponse', 0);
				$(td).html('<span class="champPerso zero">0</span>');
			}
			else{
				$(td).attr('data-reponse', '');
				$(td).html('<span class="champPerso"></span>');
				$(td).attr('idreponse', '');
			}
		}
		else{
			$(td).attr('data-reponse', msg[0].reponse);
		}

	// update du total
	var index = $(td).index();
	var tableau = $(td).closest($('.tab'));
	var count = 0;
	$(tableau).find('tbody tr').each(function(i){
		var tmp = parseInt($(this).find('td').eq(index).attr('data-reponse'));
		count += (isNaN(tmp))? 0 : tmp;
		console.log(parseInt($(this).find('td').eq(index).attr('data-reponse')));
	});

	$(tableau).find('tfoot tr').find('td').eq(index-1).html(count);


	})
	.error(function(msg) {
		$('#message-error').message({'delay': 10});
		console.log(msg);
	});

}

function initTotaux(){
		var count = new Array();
		$("#champPerso").find('tbody tr:first-child td').each(function(i){
			var to = 0;
			var index = i;

			$("#champPerso").find('tbody tr').each(function(j){
				var nb = parseInt($(this).find('td').eq(index).attr('data-reponse'));
				nb = (isNaN(nb))? 0 : nb;
				to += nb;
			});
			count.push(to);
		});

		for(var i = 2 ; i <= (count.length+1) ; i++){
			if($("#champPerso").find('tfoot tr td').eq(i-1).attr('data-typereponse') == "bool"){
				$("#champPerso").find('tfoot tr td').eq(i-1).text(count[i]);
			}
		}

	}
