$(document).ready(function(){

	//initialise le tri des inscriptions en attentes
	tri();

	// on fige la taille de l'entete du tableau à l'aide du plugin stickytableheaders
	$('table').stickyTableHeaders();
	$('.table-container').on('scroll', function(){
		$('table').stickyTableHeaders({fixedOffsetY: $(this).scrollLeft()});
	});


	// sortable ui.
	$('table tbody').sortable({
		axis: 'y',
		items: "> tr.attente",
		handle: ".handle",
		helper: function(e, tr){
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.children().each(function(index)
			{
			// Set helper cell sizes to match the original sizes
				$(this).width($originals.eq(index).width());
				$(this).css('padding', $originals.eq(index).css('padding'));
			});
				return $helper;
		},
		stop: function( event, ui ){

			var ordre = parseInt(ui.item.attr('data-ordre'));
			var id = ui.item.attr('data-idinscription');
			var idPrec =  ui.item.prev('.attente').attr('data-idinscription');
			var idSuiv =  ui.item.next('.attente').attr('data-idinscription');
			var ordrePrec =  parseInt(ui.item.prev('.attente').attr('data-ordre'));
			var ordreSuiv =  parseInt(ui.item.next('.attente').attr('data-ordre'));
			var idDiv = ui.item.parents('.tab').attr('id');
			var data = new Array();

			//au début
			if((typeof ordrePrec == 'undefined')||(typeof idPrec == 'undefined')){
				if(ordre >= ordreSuiv){
					if(ordreSuiv == 0){
						var nouvelOrdre = 0;
						var ordreSuiv = parseInt(parseInt(ui.item.next().next().attr('data-ordre')) / 2);
						if(isNaN(ordreSuiv)){ordreSuiv = 1000;}
						changeOrdre(id, nouvelOrdre);
						changeOrdre(idSuiv, ordreSuiv);
						data.push({'id':id, 'ordre':nouvelOrdre});
						data.push({'id':idSuiv, 'ordre':ordreSuiv});
					}
					else{
						var nouvelOrdre = 0;
						changeOrdre(id, nouvelOrdre);
						data.push({'id':id, 'ordre':nouvelOrdre});
					}
				}
			}

			// à la fin
			else if((typeof ordreSuiv == 'undefined')||(typeof idSuiv == 'undefined')){
				if(ordre <= ordrePrec){
					var nouvelOrdre = parseInt(parseInt(ordrePrec) + 1000);
					changeOrdre(id, nouvelOrdre);
					data.push({'id':id, 'ordre':nouvelOrdre});
				}
			}

			//entre deux lignes
			else if((typeof ordreSuiv != 'undefined')&&(typeof ordrePrec != 'undefined')){
				if((ordre < ordrePrec)||(ordre > ordreSuiv)){
					var nouvelOrdre = parseInt(ordrePrec + ((ordreSuiv - ordrePrec) /2));
					changeOrdre(id, nouvelOrdre);
					data.push({'id':id, 'ordre':nouvelOrdre});
				}
			}

			if(data.length != 0){
				$.progress.show();
				$.ajax({
					type: "POST",
					url: $('#lienAjax').text(),
					//dataType: 'json',
					data: JSON.stringify(data),
				})
				.done(function( msg ) {
					$.progress.hide();
				});
			}
			tri();
		}
	});

	// SPLIT BUTTON


	$(function() {
		$( "#controls a.btn" ).first()
			.next()
			.button({
				text: false,
				icons: {
				primary: "caret"
			}
			})
			.click(function() {
				var menu = $( this ).parent().next().show().position({
				my: "left top",
				at: "left bottom",
				of: this
			});
		$( document ).one( "click", function() {
			menu.hide();
			});
			return false;
		})
			.parent()
			.buttonset()
			.next()
			.hide()
			.menu();
	});
});

function changeOrdre(id, ordre){
	$('table tbody tr').filter('[data-idinscription="'+id+'"]').attr('data-ordre', ordre);
}

function tri(){
	$('div.tab').find('tbody').each(function(i,n){
		$(this).find('tr.attente').sortElements(function(a,b){
			return (parseInt($(a).attr('data-ordre')) > parseInt($(b).attr('data-ordre')))? 1 : -1;
		});
	});
}


// tri des élements
jQuery.fn.sortElements = (function(){

    var sort = [].sort;

    return function(comparator, getSortable) {

        getSortable = getSortable || function(){return this;};

        var placements = this.map(function(){

            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,

                // Since the element itself will change position, we have
                // to have some way of storing its original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function() {

                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }

                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);

            };

        });

        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });

    };

})();
