$(document).ready(function(){
	$('#ajouttypes').click(function(e){
		//annulation ancre
		e.preventDefault();
		
		var nouveautype = prompt('Nouveau type de structure :');
		
		if(nouveautype){
			$.ajax({
				type: "post",
				url: $('#ajouttypes').attr('data-url'),
				data: JSON.stringify({ "structure" : nouveautype })
			})
			.done(function( id ) {
				//reponse du serveur : verif si c'est bien un id qui est renvoy�
				if( !isNaN(id) ){
					
					//on ajout au dropdown
					$('#ocim_evenementsbundle_inscription_signataire_adresse_type').append($('<option>', {
						value: id,
						text: nouveautype,
					}));
					$('select#ocim_evenementsbundle_inscription_signataire_adresse_type').val(id);
					
				}
			});
		}
	})
})