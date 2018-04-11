window.onresize = resizeDiv;
function resizeDiv(){
	$("#titre").css('width', $("#content").width());
	$('#titre').wrap("<div id='titre-wrap'></div>");
	$('#titre-wrap').css('height', $('#titre').height());
	$('#titre-wrap').css('margin-bottom', $('#titre').css('margin-bottom'));
	$(".tools-message").css('right', ($(document).width()/2)-($(".tools-message").width()/2)+"px");
}


$(document).ready(function(){

	resizeDiv();

	$('#user a').click(function(){
		$(this).next('nav').animate({ height: "toggle"}, 300);
	});

	$("#nav-toggle").click(function(){
	var state = parseInt($('#content').css('margin-left')) > 200;

		$("#sidebar").animate({
			"left": (state ? -215 : 0)
		}, function(){
			resizeDiv();
		});
		$("#content").animate({
			"margin-left": (state ? 0 : 215)
		}, function(){
			resizeDiv();
		});
		if(state){
			$("#sidebar nav").addClass("disparu");
		}
		else{
			$("#sidebar nav").removeClass("disparu");
		}

	});


	//eventlistener plein ecran
	$("#fullscreen-btn").click(toggleFullScreen);

	//plein ecran
	function toggleFullScreen() {
		if (!document.fullscreenElement &&    // alternative standard method
				!document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
			if (document.documentElement.requestFullscreen) {
				document.documentElement.requestFullscreen();
			} else if (document.documentElement.mozRequestFullScreen) {
				document.documentElement.mozRequestFullScreen();
			} else if (document.documentElement.webkitRequestFullscreen) {
				document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
			}
		} else {
			if (document.cancelFullScreen) {
				document.cancelFullScreen();
			} else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
			} else if (document.webkitCancelFullScreen) {
				document.webkitCancelFullScreen();
			}
		}
	}

	// datepicker init
	// sur les element contenant data-tool='datepicker'
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

	//style des boutons, et rassemblement des boutons de control
	$('.btn-save').prepend('<i class="fa fa-save fa-fw"></i>');
	$('.btn-delete').prepend('<i class="fa fa-trash fa-fw"></i>');

	$('.btn-delete').on('click', function(e){
		e.preventDefault();
		var confirm = window.confirm("Etes-vous sûr de vouloir supprimer cette entité ?");
		if(confirm){
			$(this).closest('form').submit();
		}
	});

	//flash message
	$(".tools-message:not(.disabledonload)").css('right', ($(document).width()/2)-($(".tools-message").width()/2)+"px");
	$(".tools-message:not(.disabledonload)").message();


	//copie des boutons du footer
	var btnsave = $("#footer").find(".btn-save").clone(true);
	var btnback = $("#footer").find(".btn-back").clone(true);
	var btnedit = $("#footer").find(".btn-edit").clone(true);
	//ajout a #controls
	$("#controls").prepend(btnsave, btnedit, btnback);
	//un peu de css
	$("#controls").find(".btn-save, .btn-delete, .btn-back, btn-edit").css('display', 'inline-block').text('');
	$("#controls").find("button.btn-delete, input.btn-delete").closest('form').css('display', 'inline-block');
	//on efface le contenu pour le remplacer par des icones
	$('#controls input.btn-save, #controls button.btn-save').html('<i class="fa fa-save fa-2x fa-fw"></i>');
	$('#controls input.btn-delete, #controls button.btn-delete').html('<i class="fa fa-trash fa-2x fa-fw"></i>');
	$('#controls a.btn-back').html('<i class="fa fa-chevron-left fa-2x fa-fw"></i>');
	$('#controls a.btn-edit').html('<i class="fa fa-pencil fa-2x fa-fw"></i>');
	//ajout dattributs title aux boutons
	$('#controls .btn-back').attr('title' ,"Retour à la page précédente");
	$('#controls .btn-save').attr('title' ,"Enregistrer");
	$('#controls .btn-delete').attr('title' ,"Supprimer");
	$('#controls .btn-edit').attr('title' ,"Modifier");
	//evenement validation du formulaire
	$('#controls .btn-save').click(function(e){
		$('#footer .btn-save').closest('form').submit();
	});


});
