tinymce.init({
    mode : "textareas",
    theme : "advanced",
    force_br_newlines : false,
    force_p_newlines : false,
    forced_root_block : '',
    selector: "textarea",
    theme: "modern",
    height: 600,
    pagebreak_separator: "<div class='pagebreak'></div>",
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
   ],
   toolbar: ["sizeselect | fontselect | fontsizeselect | insertfile undo redo | styleselect | bold italic | indent outdent | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
			"Evenement | Inscription | Stagiaire | Formule | Convention | Divers"],
    image_advtab: true,
   image_list: [
   {title: 'Logo-Ocim', value: 'http://www.ocim.fr/wp-content/themes/ocim/img/logo.png'},
   {title: 'Bandeau-Ocim', value: "http://www.ocim.fr/wp-content/themes/ocim/img/bandeau-lateral-courrier.png" }
   ],


	setup: function(editor) {
        editor.addButton('Inscription', {
            type: 'menubutton',
            text: 'Inscription',
            icon: false,
            menu: [
                {text: "Date d'inscription", onclick: function() 	{editor.insertContent("{{ inscription.dateInscription|date('d/m/Y') }}"); }},
                {text: 'Statut', onclick: function() 				{editor.insertContent("{{ inscription.statut|capitalize }}"); }},
                {text: 'Attentes', onclick: function() 				{editor.insertContent("{{ inscription.attentes }}"); }},
                {text: "Contact Admin.",
                  menu: [
                {text: "Civilite", onclick: function() 			{editor.insertContent("{{ inscription.admin.civilitepretty }}"); }},
                  {text: "Nom", onclick: function() 				{editor.insertContent("{{ inscription.admin.nom }}"); }},
                  {text: "Prenom", onclick: function() 			{editor.insertContent("{{ inscription.admin.prenom }}"); }},
                  {text: "Fonction", onclick: function() 			{editor.insertContent("{{ inscription.admin.fonction }}"); }},
                  {text: "Téléphone", onclick: function() 			{editor.insertContent("{{ inscription.admin.tel }}"); }},
                  {text: "Mail", onclick: function() 			{editor.insertContent("{{ inscription.admin.mail }}"); }},
                  ]}
            ]
        });
		editor.addButton('Stagiaire', {
			type: 'menubutton',
			text: 'Stagiaire',
			icon: false,
			menu: [
    {text: 'Civilite', onclick: function() 					{editor.insertContent("{{ inscription.stagiaire.civilitepretty }}"); }},
				{text: 'Nom', onclick: function() 						{editor.insertContent("{{ inscription.stagiaire.nom }}"); }},
				{text: 'Prenom', onclick: function() 					{editor.insertContent("{{ inscription.stagiaire.prenom }}"); }},
				{text: 'Fonction', onclick: function() 					{editor.insertContent("{{ inscription.stagiaire.fonction }}"); }},
				{text: 'Telephone', onclick: function() 				{editor.insertContent("{{ inscription.stagiaire.tel }}"); }},
				{text: 'Fax', onclick: function() 						{editor.insertContent("{{ inscription.stagiaire.fax }}"); }},
				{text: 'Mail', onclick: function() 						{editor.insertContent("{{ inscription.stagiaire.mail }}"); }},
				{text: 'Nom Strucuture (adresse)', onclick: function()	{editor.insertContent("{{ inscription.stagiaire.adresse.nomStructure }}"); }},
				{text: 'Adresse', onclick: function()					{editor.insertContent("{{ inscription.stagiaire.adresse.adresse }}"); }},
				{text: 'Complement adresse', onclick: function()		{editor.insertContent("{{ inscription.stagiaire.adresse.adresseComplement }}"); }},
				{text: 'Code postal', onclick: function()				{editor.insertContent("{{ inscription.stagiaire.adresse.CP }}"); }},
				{text: 'Ville', onclick: function()						{editor.insertContent("{{ inscription.stagiaire.adresse.ville }}"); }},
				{text: 'Pays', onclick: function()						{editor.insertContent("{{ inscription.stagiaire.adresse.pays }}"); }},
				{text: 'Commentaire', onclick: function() 				{editor.insertContent("{{ inscription.stagiaire.commentaire }}"); }}
			]
		});
		editor.addButton('Evenement', {
			type: 'menubutton',
			text: 'Evenement',
			icon: false,
			menu: [
				{text: 'Intitule', onclick: function() 					{editor.insertContent("{{ evenement.intitule }}"); }},
				{text: 'Lieu', onclick: function() 						{editor.insertContent("{{ evenement.lieu }}"); }},
				{text: 'Date de debut', onclick: function() 			{editor.insertContent("{{ evenement.dateDebut|date('d/m/Y') }}"); }},
				{text: 'Date de fin', onclick: function() 				{editor.insertContent("{{ evenement.dateFin|date('d/m/Y') }}"); }},
				{text: 'Date au format text', onclick: function() 		{editor.insertContent("{{ evenement.dateText }}"); }},
				{text: 'Duree (jours)', onclick: function() 			{editor.insertContent("{{ evenement.nbJours }}"); }},
				{text: "Nombre d'heures", onclick: function() 			{editor.insertContent("{{ evenement.nbHeures }}"); }},
				{text: "Type de evenement", onclick: function() 		{editor.insertContent("{{ evenement.type }}"); }},
			]
		});
		editor.addButton('Convention', {
			type: 'menubutton',
			text: 'Convention',
			icon: false,
			menu: [
    {text: 'Numero', onclick: function() 					  	{editor.insertContent("{{ inscription.convention.numeroToString }}"); }},
				{text: "Date d'edition", onclick: function() 					{editor.insertContent("{{ inscription.convention.edition|date('d/m/Y') }}"); }},
				{text: "Signataire",
					menu: [
        {text: "Civilite", onclick: function() 			{editor.insertContent("{{ inscription.signataire.civilitepretty }}"); }},
						{text: "Nom", onclick: function() 				{editor.insertContent("{{ inscription.signataire.nom }}"); }},
						{text: "Prenom", onclick: function() 			{editor.insertContent("{{ inscription.signataire.prenom }}"); }},
						{text: "Fonction", onclick: function() 			{editor.insertContent("{{ inscription.signataire.fonction }}"); }},
						{text: "Mail", onclick: function() 			{editor.insertContent("{{ inscription.signataire.mail }}"); }},

					]},
				{text: "Organisme Financeur",
					menu: [
						{text: "Nom de la structure", onclick: function() 		{editor.insertContent("{{ inscription.signataire.adresse.nomStructure }}"); }},
						{text: "Adresse", onclick: function() 					{editor.insertContent("{{ inscription.signataire.adresse.adresse }}"); }},
						{text: "Complement d'adresse", onclick: function() 		{editor.insertContent("{{ inscription.signataire.adresse.adresseComplement }}"); }},
						{text: "Code postal", onclick: function() 				{editor.insertContent("{{ inscription.signataire.adresse.CP }}"); }},
						{text: "Ville", onclick: function() 					{editor.insertContent("{{ inscription.signataire.adresse.ville }}"); }},
						{text: "Pays", onclick: function() 						{editor.insertContent("{{ inscription.signataire.adresse.pays }}"); }},
						{text: "Type de structure", onclick: function() 						{editor.insertContent("{{ inscription.signataire.adresse.type.type }}"); }},
					]},
			]
		});
		editor.addButton('Formule', {
			type: 'menubutton',
			text: 'Formule',
			icon: false,
			menu: [
				{text: 'Description', onclick: function() 					{editor.insertContent("{{ inscription.evenementformule.formule.description }}"); }},
				{text: 'Tarif', onclick: function() 						{editor.insertContent("{{ inscription.evenementformule.formule.tarif }}"); }},
			]
		});
    editor.addButton('Divers', {
      type: 'menubutton',
      text: 'Divers',
      icon: false,
      menu: [
        {text: 'Date Abbrégée (dd/mm/yyyy)', onclick: function() 					{editor.insertContent("{{ date_abbr }}"); }},
        {text: 'Date', onclick: function() 						{editor.insertContent("{{ date }}"); }},
      ]
    });
	},
 });
