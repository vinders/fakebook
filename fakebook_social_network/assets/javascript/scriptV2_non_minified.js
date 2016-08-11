//Gestion d'utilisateurs connectés
( function( $ ) {

	//-Déclaration de variables globales-
	var imageActuelle = 0, btn, link,
		domain = 'http://localhost/curl/index.php', //http://curl.vinders.be/index.php
		rondChargement = "data:image/gif;base64,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";
		
	//-Méthodes-
	
	//--afficher le chargement lors d'une publication
	var cliquerPublier = function(e){
		$(".toolbar button").css({color:'rgba(40,60,80,.2)',position:'relative'}).append('<b id="loading_curl"></b>');
		$(".toolbar b").css({display:'inline-block',background:"url('"+rondChargement+"') center no-repeat",width:20,height:20,position:'absolute',left:'50%',top:'50%',marginLeft:'-10px',marginTop:'-10px'});
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
	
	//--bloquer l'actualisation en cas de clic de modification
	var modifierPublication = function(e){
		$('footer').append('<b id="loading_curl"></b>');
	};//fin modifierPublication
	
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
		var $id = parseInt(e.target.getAttribute('class').substr(1),10),
			url = domain+'/ajax/supprimer/'+$id;
		$.ajax({
			url: url,
			success: function(){
				location.reload(false);
			},
			error: function (){
				window.location = domain+"/article/supprimer/"+$id;
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
		$("body").append('<div id="dialog_box" class="rupt"><p>Êtes-vous sûr de retirer cet ami&nbsp;?</p><button class="s'+id+'">Supprimer</button><a class="btn_gray" href="#">Annuler</a></div><div id="dialog_back"></div>');
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
		var $cible = parseInt(e.target.getAttribute('class').substr(1),10),
			url = domain+'/ajax/rompre/'+$cible;
		$.ajax({
			url: url,
			success: function(){
				location.reload(false);
			},
			error: function (){
				window.location = domain+"/user/rompre/"+$cible;
			}
		}); //type:"POST",data:({cible:$cible})
		return false;
	};//fin confirmerRupture

	//--fermer la boîte de confirmation en cas d'annulation
	var fermerBoite = function(e){
		e.preventDefault();
		link[0].removeEventListener('click',fermerBoite,false);
		if($("#dialog_box")[0].getAttribute('class') === 'rupt'){
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
		var cible = urlarray[urlarray.length-1],
			url;
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
			error: function (){
				window.location = domain+"/article/lister/wall/"+cible;
			}
		});
		return false;
	};//fin gererRequete
	
	//actualiser pour mettre à jour amis/requêtes et publications listées
	var actualiser = function(){
		if($("#dialog_box").length === 0 && $("textarea:focus").length === 0 && $("#loading_curl").length === 0){
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
			$(".btn_modif").each(function(){$(this).on("click",modifierPublication);});
		}
		if($(".inviter").length > 0){
			$("#requests .btn2_blue,#requests .btn2_gray,#requests .btn2_red").each(function(){$(this)[0].addEventListener("click",gererRequete,false);});
		}
		if($(".side_circle0").length > 0){
			$(".btn2_gray").each(function(){$(this).on("click",effacerAmi);});
		}
		
		if($(".publication").length>0 || $("#no_content").length>0 || $(".side_circle0").length>0){
			setInterval(actualiser,16000);
		}
	} );
} )( jQuery );