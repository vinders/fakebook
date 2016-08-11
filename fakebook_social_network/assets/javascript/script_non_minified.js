//Gestion d'utilisateurs connectés
( function( $ ) {

	//-Déclaration de variables globales-
	var imageActuelle = 0, btn, link,
		domain = 'http://localhost/curl/index.php';
		rondChargement = "data:image/gif;base64,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";
	
	//-Méthodes-
	
	//--afficher le chargement lors d'une publication
	var cliquerPublier = function(e){
		e.target.setAttribute('color','#edeff4');
		$("body").append('<span id="loading_curl" href="#"></span>');
		$(".toolbar").css({background:"#edeff4 url('"+rondChargement+"') 45% 96% no-repeat"});
	};//fin cliquerPublier
	
	//--afficher les boutons de changement d'image lors d'une publication
	var creerGalerie = function(){
		$("#prev").show();
		$("#next").show();
		$("#prev")[0].addEventListener('click',changerPrecedent,false);
		$("#next")[0].addEventListener('click',changerSuivant,false);
	};//fin creerGalerie
	var changerPrecedent = function(e){
		e.preventDefault();
		if(imageActuelle > 0){
			--imageActuelle;
			changerImage(imageActuelle);
		}else{
			var nombreImages = $(".img_gallery").length;
			imageActuelle = nombreImages - 1;
			changerImage(imageActuelle);
		}
		return false;
	};//fin changerPrecedent
	var changerSuivant = function(e){
		e.preventDefault();
		var nombreImages = $(".img_gallery").length;
		if(imageActuelle < (nombreImages - 1)){
			++imageActuelle;
			changerImage(imageActuelle);
		}else{
			imageActuelle = 0;
			changerImage(imageActuelle);
		}
		return false;
	};//fin changerSuivant
	var changerImage = function(image){
		var $img = $(".curl_content img"),
			url = $(".img_gallery")[image].getAttribute('value');
		$img.fadeOut('fast',function(){$img[0].setAttribute('src',url);});
		$("#img_input")[0].setAttribute('value',url);
		$img.fadeIn('slow');
	};//fin changerImage
	
	//--confirmer la suppression d'un article sans changer de page
	var effacerPublication = function(e){
		e.preventDefault();
		var $url = $(this).attr('href');
		var arrayurl = $url.split('/');
		var id = arrayurl[arrayurl.length-1];
		$("body").append('<div id="dialog_box" class="spr"><p>Êtes-vous sûr de supprimer cet article&nbsp;?</p><button class="s'+id+'">Supprimer</button><a class="btn_gray" href="#">Annuler</a></div><div id="dialog_back"></div>');
		link = $("#dialog_box a");
		btn = $("#dialog_box button");
		link.css({marginLeft:8,position:'relative',top:'1px'});
		btn.css({borderRadius:'3px',lineHeight:1.8});
		link[0].addEventListener('click',fermerBoite,false);
		btn[0].addEventListener('click',confirmerSuppression,false);
	};//fin effacerPublication
	var confirmerSuppression = function(e){
		e.preventDefault();
		var $id = parseInt(e.target.getAttribute('class').substr(1)),
			url = domain+'/ajax/supprimer/'+$id;
		$.ajax({
			url: url,
			success: function(){
				location.reload(false);
			},
			error: function (xhr, ajaxOptions, thrownError){
				alert('erreur '+xhr.status+': '+thrownError);
			}
		});
		return false;
	};//fin confirmerSuppression
	
	//--effacer un ami sans quitter la liste d'amis
	var effacerAmi = function(e){
		e.preventDefault();
		var $url = $(this).attr('href');
		var arrayurl = $url.split('/');
		var id = arrayurl[arrayurl.length-1];
		$("body").append('<div id="dialog_box" class="rupt"><p>Êtes-vous sûr d\'effacer cet ami&nbsp;?</p><button class="s'+id+'">Supprimer</button><a class="btn_gray" href="#">Annuler</a></div><div id="dialog_back"></div>');
		link = $("#dialog_box a");
		btn = $("#dialog_box button");
		link.css({marginLeft:8,position:'relative',top:'1px'});
		btn.css({borderRadius:'3px',lineHeight:1.8});
		link[0].addEventListener('click',fermerBoite,false);
		btn[0].addEventListener('click',confirmerRupture,false);
		return false;
	};//fin effacerAmi
	var confirmerRupture = function(e){
		e.preventDefault();
		var $cible = parseInt(e.target.getAttribute('class').substr(1)),
			url = domain+'/ajax/rompre/'+$cible;
		$.ajax({
			url: url,
			success: function(){
				location.reload(false);
			},
			error: function (xhr, ajaxOptions, thrownError){
				alert('erreur '+xhr.status+': '+thrownError);
			}
		}); //type:"POST",data:({cible:$cible})
		return false;
	};//fin confirmerRupture

	//--fermer la boîte de confirmation en cas d'annulation
	var fermerBoite = function(e){
		e.preventDefault();
		link[0].removeEventListener('click',fermerBoite,false);
		if($("#dialog_box")[0].getAttribute('class') == 'rupt'){
			btn[0].removeEventListener('click',confirmerRupture,false);
		}else{
			btn[0].removeEventListener('click',confirmerSuppression,false);
		}
		document.body.removeChild(document.getElementById('dialog_box'));
		document.body.removeChild(document.getElementById('dialog_back'));
		return false;
	};
	
	//--répondre aux notifications de requêtes sans changer de page
	var gererRequete = function(e){
		e.preventDefault();
		var urlarray = e.target.getAttribute('href').split('/');
		var cible = urlarray[urlarray.length-1];
		switch(e.target.getAttribute('class')){
			case 'btn2_blue': url = domain+'/ajax/lier/'+cible;break;
			case 'btn2_gray': url = domain+'/ajax/ignorer/'+cible;break;
			case 'btn2_red': url = domain+'/ajax/bloquer/'+cible;break;
		}
		$.ajax({
			url: url,
			success: function(){
				location.reload(false);
			},
			error: function (xhr, ajaxOptions, thrownError){
				alert('erreur '+xhr.status+': '+thrownError);
			}
		});
		return false;
	};//fin gererRequete
	
	//actualiser pour mettre à jour amis/requêtes et publications listées
	var actualiser = function(){
		if($("#dialog_box").length == 0 && $("textarea:focus").length == 0 && $("#loading_curl").length == 0){
			location.reload(false);
		}
	};
	
	//-Lancement-
	
	$( function () {
		if($(".toolbar").length > 0){
			$(".toolbar button")[0].addEventListener('click',cliquerPublier,false);
		}
		if($(".img_gallery").length > 1){
			creerGalerie();
		}
		if($(".publication").length > 0){
			$(".btn_suppr").each(function(){$(this).on("click",effacerPublication);});
		}
		if($(".inviter").length > 0){
			$("#requests .btn2_blue,#requests .btn2_gray,#requests .btn2_red").each(function(){$(this)[0].addEventListener("click",gererRequete,false);});
		}
		if($(".side_circle0").length > 0){
			$(".btn2_gray").each(function(){$(this).on("click",effacerAmi);});
		}
		
		if($(".publication").length>0 || $("#no_content").length>0 || $(".side_circle0").length>0){
			var timer = setInterval(actualiser,16000);
		}
	} );
} )( jQuery );