<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	//chargement du modèle et des helpers
	public function __construct(){
		parent::__construct();
		$this->load->model('m_user');
		$this->load->helper('h_image');
	}
	//action par défaut
	public function index(){
		$this->connecter();
	}
	
//-------------------------
// Connexion et déconnexion
//-------------------------
	
	// Connexion de l'utilisateur
	//---------------------------
	public function connecter(){
	
		//Rafraîchissement et redirection si déjà connecté
		if($this->session->userdata('vdfbcurl_connect')){
			//rafraîchissement du cercle, blacklist, requêtes
			$this->_definirSessions($this->session->userdata('vdfbcurl_connect'));
			//redirection vers publications du cercle
			redirect(site_url().'/article/lister/circle');
			exit();
		}
		
		//Reconnexion et mise en session si cookie présent
		if($this->input->cookie('vdfbcurl_connect')){
			//mise en session de la connexion
			$CI=$this->input->cookie('vdfbcurl_connect');
			$this->input->set_cookie(array('name'=>'vdfbcurl_connect','value'=>$CI['userId'],'expire'=>600000));
			$this->session->set_userdata('vdfbcurl_connect',$CI['userId']);
			//mise en session de cercle, blacklist, requêtes
			$this->_definirSessions($CI['userId']);
			//redirection vers publications du cercle
			redirect(site_url().'/article/lister/circle');
			exit();
		}

		//Connexion si pas encore connecté
		if($this->input->post('login')){
			$pseudo=strtolower($this->input->post('login'));
			$pass=$this->input->post('pass');
			if($this->m_user->connect($pseudo,$pass)){
				//mise en session de la connexion
				$user=$this->m_user->getFromName($pseudo);
				$userId=$user->auteur;
				if($this->input->post('remember')){
					$this->input->set_cookie(array('name'=>'vdfbcurl_connect','value'=>$userId,'expire'=>600000));
				}
				$this->session->set_userdata('vdfbcurl_connect',$userId);
				//mise en session de cercle, blacklist, requêtes
				$this->_definirSessions($userId);
				//redirection vers publications du cercle
				redirect(site_url().'/article/lister/circle');
				exit();
				
			//Erreur de connexion
			}else{
				$data['erreurConnexion']='Login et/ou mot de passe incorrect(s).';
				$data['erreurInscription']='';
				$dataLayout['vue']=$this->load->view('connecter',$data, true);
				$dataLayout['titre']='Connexion';
				$this->load->view('layout', $dataLayout);
			}
			
		//Page de connexion et inscription
		}else{
			$data['erreurConnexion']='';
			$data['erreurInscription']='';
			$dataLayout['vue']=$this->load->view('connecter',$data, true);
			$dataLayout['titre']='Connexion';
			$this->load->view('layout', $dataLayout);
		}
	}//fin connecter

	// Déconnexion de l'utilisateur
	//-----------------------------
	public function deconnecter(){
		//Effacement cookies et session
		if($this->input->cookie('vdfbcurl_connect')){
			$this->input->set_cookie(array('name'=>'vdfbcurl_connect','value'=>'','expire'=>0));
		}
		$this->session->unset_userdata('vdfbcurl_connect');
		redirect(site_url());
	}//fin deconnecter
	
	
//------------------------------
// Gestion de compte utilisateur
//------------------------------

	// Consulter ses informations
	//---------------------------
	public function voir(){
		$id=$this->session->userdata('vdfbcurl_connect');
		$data["infos"]=$this->m_user->getOne($id);
		$data["current"]=7;
		$dataLayout['vue']=$this->load->view('voir',$data, true);
		$dataLayout['titre']='Paramètres actuels';
		$this->load->view('layout', $dataLayout);
    }//fin voir
	
	// Création d'un compte utilisateur
	//---------------------------------
	public function ajouter(){
		//Récupération des informations du formulaire
		$data['pseudo']=strtolower(strip_tags($this->input->post('pseudo')));
		$data['email']=strip_tags($this->input->post('email'));
		$data['nom']=strip_tags($this->input->post('nom'));
		$data['pass']=strip_tags($this->input->post('addpass'));
		$data['pass2']=strip_tags($this->input->post('addpass2'));
		
		//Vérifications de la validité des valeurs entrées
		if($data['pseudo']!='' && $data['email']!='' && $data['pass']!='' && $data['nom']!='' && $data['pseudo']!=' ' && $data['email']!=' ' && $data['pass']!=' ' && $data['nom']!=' '){
			if($this->_verifierEmail($data['email'])){
				if(!$this->m_user->verify($data['pseudo'],'pseudo')){
					if(strlen($data['pass'])>4 && strlen($data['pass'])<16 && $data['pass']==$data['pass2']){
						$verif=$this->input->post('verif');
						$verifok=$this->input->post('verifok');
						if($verif==$verifok){
							//ajout du compte utilisateur
							$this->m_user->add($data);
							$data['erreurInscription']='ok';
							$data['confirmerInscription']=1;
						}else{
							$data['erreurInscription']='Résultat de vérification incorrect.';
						}
					}else{
						$data['erreurInscription']='Le mot de passe doit être compris entre 4 et 16 caractères et identique à la confirmation';
					}
				}else{
					$data['erreurInscription']='Ce pseudonyme ou email existe déjà.';
				}
			}else{
				$data['erreurInscription']='Email invalide';
			}
		}else{
			$data['erreurInscription']='Champ(s) incomplet(s).';
		}
		$data['erreurConnexion']='';
		
		//Rechargement de page d'inscription en cas d'erreur
		$dataLayout['vue']=$this->load->view('connecter',$data, true);
		$dataLayout['titre']='Connexion';
		$this->load->view('layout', $dataLayout);
	}//fin ajouter
	
	// Paramétrage d'un compte utilisateur
	//------------------------------------
	public function modifier(){
		$id=$this->session->userdata('vdfbcurl_connect');
		
		//Modification du compte si appliqué
		if($this->input->post('pseudo')){
			$data["pseudo"]=$this->input->post('pseudo');
			$data["pass"]=$this->input->post('pass');
			$data["nom"]=$this->input->post('nom');
			$data["email"]=$this->input->post('email');
			
			//vérification du mot de passe et des entrées
			if($this->m_user->connect($data["pseudo"],$data["pass"])){
				$data["auteur"]=$id;
				//changement éventuel de mot de passe
				if($this->input->post('newpass') && strlen($this->input->post('newpass'))){
					$newpass=$this->input->post('newpass');
					if($this->input->post('newpass2') && $newpass==$this->input->post('newpass2')){
						if(strlen($newpass)>4 && strlen($newpass)<16){
							$data["pass"]=$newpass;
						}else{
							$data["erreur"]='Le mot de passe doit comporter entre 4 et 16 caractères.';
						}
					}else{
						$data["erreur"]='La confirmation du nouveau mot de passe est incorrecte.';
					}
				}
				//upload d'image d'avatar
				if(isset($_FILES['image']) && $_FILES['image']['size']!=0){
					if($_FILES['image']['error']==0){
						$erreur_upload=recupererAvatar($id); //la fonction retourne 1 si problème
					}else{
						$erreur_upload=1;
					}
					if($erreur_upload){
						$this->session->set_flashdata('erreur_img', 'Impossible de sauvegarder l\'image');
					}
				}
			//mauvais mot de passe
			}else{
				$data["erreur"]='Mot de passe incorrect.';
			}
			
			//Modification du compte si aucune erreur
			if(!isset($data["erreur"])){
					$this->m_user->update($data);
					redirect(site_url().'/user/voir');
			}else{
				$data["infos"]=$this->m_user->getOne($id);
				$dataLayout['vue']=$this->load->view('parametrer',$data, true);
				$dataLayout['titre']='Modification des paramètres';
				$this->load->view('layout', $dataLayout);
			}
			
		//Page de modification des informations personnelles
		}else{
			$data["infos"]=$this->m_user->getOne($id);
			$dataLayout['vue']=$this->load->view('parametrer',$data, true);
			$dataLayout['titre']='Modification des paramètres';
			$this->load->view('layout', $dataLayout);
		}
	}//fin modifier
	
	// Suppression d'un compte utilisateur
	//------------------------------------
	public function supprimer(){
		$id=$this->session->userdata('vdfbcurl_connect');
		$data["infos"]=$this->m_user->getOne($id);
		
		//Suppression du compte si confirmé
		if($this->input->post('pass')){
			$pass=$this->input->post('pass');
			//vérification du mot de passe et suppression
			if($this->m_user->connect($data["infos"]->pseudo,$pass)){
				//retrait du cercle des amis
				$amis=explode(",", $data["infos"]->cercle);
				foreach($amis as $ami){
					//$this->m_user->removeCircle($id,$ami);
					$this->m_user->removeCircle($ami,$id);
				}
				//suppression des requêtes liées à l'utilisateur
				$this->m_user->deleteAllRequests($id);
				//suppression de l'utilisateur et de tous ses articles
				$articles=$this->m_user->delete($id);
				foreach($articles as $article){
					$filename='./'.IMG_DIR.'article'.$article->id.'.jpg';
					if(file_exists($filename)){
						unlink($filename);
					}
				}
				//suppression des images de l'utilisateur
				$avatar='./'.IMG_DIR.'avatar'.$id.'.jpg';
				$avthumb='./'.IMG_DIR.'avthumb'.$id.'.jpg';
				if(file_exists($avatar)){
					unlink($avatar);
				}if(file_exists($avthumb)){
					unlink($avthumb);
				}
				//suppression des informations en session
				$this->session->unset_userdata('blacklist');
				$this->session->unset_userdata('circle');
				$this->session->unset_userdata('vdfbcurl_connect');
				//redirection vers index
				$this->session->set_flashdata('desinscription', 'Désinscription effectuée avec succès.');
				redirect(site_url().'/user/connecter');
				
			//Erreur de mot de passe
			}else{
				$data["erreur"]='Mot de passe incorrect.';
				$dataLayout['vue']=$this->load->view('desinscrire',$data, true);
				$dataLayout['titre']='Suppression du compte';
				$this->load->view('layout', $dataLayout);
			}
			
		//Page de confirmation de suppression du compte
		}else{
			$dataLayout['vue']=$this->load->view('desinscrire',$data, true);
			$dataLayout['titre']='Suppression du compte';
			$this->load->view('layout', $dataLayout);
		}
	}//fin supprimer
	
	// Récupérer un mot de passe perdu
	//--------------------------------
	public function recuperer(){
		//Création et envoi de nouveau mot de passe si appliqué
		if($this->input->post('info')){
			$data['info']=$this->input->post('info');
			$verif=$this->input->post('verif');
			$verifok=$this->input->post('verifok');
			//vérifications
			if($verif==$verifok){
				if($this->_verifierEmail($data['info'])){
					$type='email';
				}else{
					$type='pseudo';
				}
				if($this->m_user->verify($data['info'],$type)){
					//création et envoi de mot de passe
					$length=8;
					$pass=$this->_genererPass($length);
					$recall=$this->m_user->recall($data['info'],$type,$pass);
					if($type='email'){
						$pseudo=$recall->pseudo;
						$email=$data['info'];
					}else{
						$pseudo=$data['info'];
						$email=$recall->email;
					}					
					$send='Informations de connexion Fakebook - Identifiant :'.$pseudo.' - Mot de passe :'.$pass.'</p>';
					$title='Fakebook - Informations de connexion';
					mail($email, $title, $send); 
					$data['message']='ok'.$send;
				}else{
					$data['message']='Ce pseudo ou email est introuvable.';
				}
			}else{
				$data['message']='Résultat de vérification incorrect.';
			}
		}
		
		//Page de récupération demandant email ou pseudo
		if(!isset($data['message'])){
			$data['message']='';
		}
		$dataLayout['vue']=$this->load->view('recuperer',$data, true);
		$dataLayout['titre']='Récupérer ses informations de connexion';
		$this->load->view('layout', $dataLayout);
    }//fin recuperer
	
	
//-------------------------
// Gestion du cercle d'amis
//-------------------------
	
	// Voir son cercle d'amis
	//-----------------------
	public function voircercle(){
	
		//Rafraîchissement des informations cercle, blacklist, requêtes en session
		$session = $this->session->userdata('vdfbcurl_connect');
		$requetes = $this->m_user->getAllRequests($session);
		if($requetes !== false){
			foreach($requetes as $requete){
				$demandes[$requete->pseudo]=$requete->demandeur;
			}
			$this->session->set_userdata('requests',$demandes);
		}else{
			$this->session->unset_userdata('requests');
		}
		//Actualisation du cercle
		$user=$this->m_user->getOne($session);
		$cercle=explode(',',$user->cercle);
		$this->session->set_userdata('circle',$cercle);
		$blacklist=$this->m_user->getBlackList($session);
		$this->session->set_userdata('blacklist',$blacklist);
		//Détermination du nombre et récupération des noms
		if($cercle[0]!=NULL && $cercle[0]!='' && $cercle[0]!=' '){
			$amis=array();
			$data["number"]=count($cercle);
			for($i=0;$i<$data["number"];$i++){
				$amis[$i]=$this->m_user->getOne($cercle[$i]);
			}
			$data["amis"]=$amis;
		}else{
			$data["number"]=0;
		}
		//Chargement de la page de liste
		$data["current"]=6;
		$dataLayout['vue']=$this->load->view('voircercle',$data, true);
		$dataLayout['titre']='Liste des amis';
		$this->load->view('layout', $dataLayout);
    }//fin voircercle
	
	// Faire une demande d'ajout d'ami
	//--------------------------------
	function demander(){
		$demandeur = $this->session->userdata('vdfbcurl_connect');
		$cible = $this->uri->segment(3);
		
		//Actualisation et vérification que pas déjà ami
		$user=$this->m_user->getOne($demandeur);
		$cercle=explode(',',$user->cercle);
		$prerequest=$this->m_user->getRequest($cible,$demandeur);//1 si premier demande(ici:l'autre), 2 si deuxième(ici:déjà demandé), 0 si non trouvé
		
		//Envoi de requête si non trouvé
		if(!in_array($cible,$cercle) && $prerequest==0){
			$this->m_user->setRequest($demandeur,$cible);
			
		//Ajout d'ami s'il avait lui aussi demandé
		}elseif($prerequest==1){
			redirect(site_url().'/user/confirmer_lien/lier/'.$cible);
			exit();
		}
		redirect(site_url().'/article/lister/wall/'.$cible);
	}//fin demander
	
	// Répondre à une demande d'ajout d'ami
	//-------------------------------------
	function confirmer_lien(){
		$cible = $this->session->userdata('vdfbcurl_connect');
		$demandeur = $this->uri->segment(4);
		
		//Vérification que la requête existe et dans le bon sens
		if($this->m_user->getRequest($demandeur,$cible)==1){
			//accepter et ajouter un ami
			if($this->uri->segment(3)=='lier'){
				$blacklist=array_search($demandeur, $this->session->userdata('blacklist'));
				if($blacklist !== false){
					//unset($this->session->userdata('blacklist')[$blacklist]);
					$userdata = $this->session->userdata('blacklist');
					unset($userdata[$blacklist]);
					$this->session->set_userdata('blacklist',$userdata);
				}
				$this->m_user->acceptCircle($demandeur,$cible);
				$this->m_user->acceptCircle($cible,$demandeur);
				$this->m_user->deleteRequest($demandeur,$cible);
				$cercle = $this->session->userdata('circle');
				array_push($cercle,$demandeur);
				$this->session->set_userdata('circle',$cercle);
			//ignorer et supprimer la requête
			}elseif($this->uri->segment(3)=='ignorer'){
				$this->m_user->deleteRequest($demandeur,$cible);
			//bloquer le demandeur et blacklister
			}elseif($this->uri->segment(3)=='bloquer'){
				if(!in_array($demandeur,$this->session->userdata('blacklist'))){
					$blacklist=$this->session->userdata('blacklist');
					array_push($blacklist,$demandeur);
					$this->session->set_userdata('blacklist',$blacklist);
				}
				$this->m_user->setBlackList($demandeur,$cible);
			}
		}
		redirect(site_url().'/article/lister/wall/'.$demandeur);
	}//fin bloquer
	
	// Effacer un ami de son cercle
	//-----------------------------
	function rompre(){
		$demandeur = $this->session->userdata('vdfbcurl_connect');
		$cible = $this->uri->segment(3);
		
		//Effacement de l'ami en db et session si appliqué
		if($this->input->post('cible')){
			$this->m_user->removeCircle($demandeur,$cible);
			$this->m_user->removeCircle($cible,$demandeur);
			$position=array_search($cible, $this->session->userdata('circle'));
			//$this->session->unset_userdata('circle')[$position];
			$userdata = $this->session->userdata('circle');
			unset($userdata[$position]);
			$this->session->set_userdata('circle',$userdata);
			redirect(site_url().'/article/lister/wall');
			
		//Page de confirmation de l'effacement
		}else{
			$data["infos"] = $this->m_user->getOne($cible);
			$data["cible"] = $cible;
			$dataLayout['vue']=$this->load->view('rompre',$data, true);
			$dataLayout['titre']='Retirer un ami';
			$this->load->view('layout', $dataLayout);
		}
	}//fin rompre
	
	
//----------------------------
// Fonctions génériques utiles
//----------------------------
	
	// Création d'un nouveau mot de passe
	//-----------------------------------
	function _genererPass($length){
		$alpha=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9');
		$pass='';
		for($i=0; $i<$length; $i++){
			$nb=rand(0,35);
			$pass.=$alpha[$nb];
		}
		return $pass;
	}//fin _genererPass
	
	// Vérification d'une adresse email
	//---------------------------------
	function _verifierEmail($email) {
		if(preg_match("/^([a-zA-Z0-9ÁÀÂÄáàâäÇçÉÈÊËéèêëÍÌÎÏíìîïÑñÕÓÒÔÖõóòôöŞşÚÙÛÜúùûüÝýÿ\β\?\[\]\(\)\'\&\$\=\Ø\ß\Æ\Œ\œ\._\#-])+@([a-zA-Z0-9ÁÀÂÄáàâäÇçÉÈÊËéèêëÍÌÎÏíìîïÑñÕÓÒÔÖõóòôöŞşÚÙÛÜúùûüÝýÿ\β\?\[\]\(\)\'\&\$\=\Ø\ß\Æ\Œ\œ_\#-])+\.([a-zA-Z0-9ÁÀÂÄáàâäÇçÉÈÊËéèêëÍÌÎÏíìîïÑñÕÓÒÔÖõóòôöŞşÚÙÛÜúùûüÝýÿ\β\?\[\]\(\)\'\&\$\=\Ø\ß\Æ\Œ\œ_\#-])+/",$email)){
			list($username,$domain)=explode('@',$email);
			if(!checkdnsrr($domain,'MX')) {
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}//fin _verifierEmail
	
	// Mise en session d'informations
	//-------------------------------
	function _definirSessions($id){
		//session du cercle d'amis
		$user=$this->m_user->getOne($id);
		$cercle=explode(',',$user->cercle);
		$this->session->set_userdata('circle',$cercle);
		//session de blacklist
		$blacklist=$this->m_user->getBlackList($id);
		$this->session->set_userdata('blacklist',$blacklist);
		//session de requêtes
		$requetes = $this->m_user->getAllRequests($id);
		if($requetes !== false){
			foreach($requetes as $requete){
				$demandes[$requete->pseudo]=$requete->demandeur;
			}
			$this->session->set_userdata('requests',$demandes);
		}else{
			$this->session->unset_userdata('requests');
		}
	}//fin _definirSessions
}//fin User