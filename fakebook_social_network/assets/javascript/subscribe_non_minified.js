var lancer = function(){

	//-Déclaration de variables-
	
	var submit = document.getElementById('subscribe_form').getElementsByTagName('button')[0],
		pseudo = document.getElementById('pseudo'),
		nom = document.getElementById('nom'),
		email = document.getElementById('email'),
		addpass = document.getElementById('addpass'),
		addpass2 = document.getElementById('addpass2'),
		verif = document.getElementById('verif'),
		inputs = document.getElementsByTagName('input'),
		veriftotal,
		error = document.createElement("p");

	//-Méthodes-
	
	var envoyer = function(e){
		if(verif.value === veriftotal){
			var v1 = changerCouleur(pseudo),
				v2 = changerCouleur(nom),
				v3 = changerCouleur(email),
				v4 = changerCouleur(addpass),
				v5 = changerCouleur(addpass2),
				v6 = changerCouleur(verif);
			if(v1 && v2 && v3 && v4 && v5 && v6 && addpass.value === addpass2.value){
				if(addpass.value.length < 4 || addpass.value.length > 16){
					e.preventDefault();
					error.innerHTML = 'Le mot de passe doit contenir 4 à 16 caractères.';
				}
			}else{
				e.preventDefault();
				error.innerHTML = 'Champ(s) incomplet(s).';
			}
		}else{
			e.preventDefault();
			error.innerHTML = 'Résultat de vérification incorrect.';
		}
	};//fin envoyer

	var verifier = function(e){
		changerCouleur(e.target);
	};//fin verifier

	var confirmer = function(e){
		if(e.target.value === addpass.value){
			e.target.style.border = '1px solid green';
		}else{
			e.target.style.border = '1px solid red';
		}
	};//fin confirmer
	
	var valider = function(e){
		if(e.target.value === veriftotal){
			e.target.style.border = '1px solid green';
		}else{
			e.target.style.border = '1px solid red';
		}
	};//fin valider
	
	var changerCouleur = function(element){
		if(element.value != '' && element.value !== undefined && element.value !== ' '){
			element.style.border = '1px solid green';
			return true;
		}else{
			element.style.border = '1px solid red';
			return false;
		}
	};//fin changerCouleur

	//-Lancement-
	
	for (var i = 0; i < inputs.length; i++){
		if(inputs[i].getAttribute("name") === 'verifok'){
			veriftotal = inputs[i].value;
		}
	}
	error.setAttribute('class','error');
	document.getElementById('subscribe_form').appendChild(error);
	
	submit.addEventListener('click',envoyer,false);
	pseudo.addEventListener('blur',verifier,false);
	nom.addEventListener('blur',verifier,false);
	email.addEventListener('blur',verifier,false);
	addpass.addEventListener('blur',verifier,false);
	addpass2.addEventListener('blur',confirmer,false);
	verif.addEventListener('blur',valider,false);
};
window.addEventListener('load',lancer,false);