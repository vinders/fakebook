<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {

	//chargement du modèle et des helpers
	public function __construct(){
		parent::__construct();
		$this->load->model('m_article');
		$this->load->model('m_user');
		$this->load->helper('h_url');
		$this->load->helper('h_image');
	}
	//action par défaut
	public function index(){
		$this->lister();
	}
	
	//----------------------------
	// Lister les articles publiés
	//----------------------------
	public function lister(){
		if($this->session->userdata('vdfbcurl_connect')){
		
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
			$user=$this->m_user->getOne($session);
			$cercle=explode(',',$user->cercle);
			$this->session->set_userdata('circle',$cercle);
			$blacklist=$this->m_user->getBlackList($session);
			$this->session->set_userdata('blacklist',$blacklist);
			
			//Analyse du type de liste à afficher
			//mur d'une seule personne
			if($this->uri->segment(3)=='wall'){
				//mur d'une personne particulière
				if($this->uri->segment(4)){
					$dataList['current_id']=$this->uri->segment(4);
					$dataUser=$this->m_user->getOne($dataList['current_id']);
					$dataList['current_pseudo'] = $dataUser->pseudo;
					$dataList['current_nom'] = $dataUser->nom;
					if(in_array($dataList['current_id'],$cercle)){ //profil d'un ami
						$partage=1;
						$dataList['current'] = 4;
					}elseif($dataList['current_id']==$session){ //profil personnel
						$partage=0;
						$dataList['current'] = 0;
					}else{ //profil étranger
						$partage=2;
						$dataList['current'] = 5;
						if(!in_array($dataList['current_id'],$this->session->userdata('blacklist'))){
							$dataList['current_status'] = $this->m_user->getRequest($dataList['current_id'],$session); //0 non trouvé, 1 demandeur, 2 cible
						}else{
							$dataList['current_status'] = 3; //blacklisté
						}
					}
				//mur privé
				}else{
					$dataList['current_id']=$session;
					$dataUser=$this->m_user->getOne($dataList['current_id']);
					$dataList['current_pseudo'] = $dataUser->pseudo;
					$dataList['current_nom'] = $dataUser->nom;
					$partage=0;
					$dataList['current'] = 0;
				}
				$dataList['articles'] = $this->m_article->listOne($dataList['current_id'],$partage);
			}
			//mur du cercle d'amis (par défaut)
			elseif($this->uri->segment(3)=='circle'){
				$dataList['articles'] = $this->m_article->listCircle($session,$cercle);
				$dataList['current'] = 1;
			}
			//mur public
			else{
				$dataList['articles'] = $this->m_article->listAll();
				$dataList['current'] = 2;
			}
			
			//Chargement de la page de liste
			$dataLayout['vue']=$this->load->view('lister',$dataList, true);
			$dataLayout['titre']='Liste des articles';
			$this->load->view('layout', $dataLayout);
		}else{
			redirect(site_url());
		}
	}//fin lister
	
	
	//--------------------------
	// Ajouter un nouvel article
	//--------------------------
	public function ajouter(){
	
		//Publication de l'article si appliqué
		if($this->input->post('url')){
			$data["url"]=$this->input->post('url');
			$data["titre"]=$this->input->post('titre');
			if($data["titre"]=='' || $data["titre"]==' ' || $data["titre"]=='undefined' || $data["titre"]==NULL){
				$data["titre"]=$data["url"];
			}
			$data["description"]=$this->input->post('description');
			$data["auteur"]=$this->session->userdata('vdfbcurl_connect');
			$data["partage"]=$this->input->post('partage');
			$data["date"]=date("H:i\, d\/m\/Y");
			$this->m_article->add($data);
			//ajout d'une image si nécessaire
			if($this->input->post('image')){
				$dernier = $this->m_article->getLast();
				$urlimg=$this->input->post('image');
				$id=$dernier->id;
				recupererImage($urlimg,$id);
			}
			//redirection en fonction du choix de partage
			switch ($data["partage"]){
				case 0 : redirect(site_url().'/article/lister/wall'); break;
				case 1 : redirect(site_url().'/article/lister/circle'); break;
				case 2 : redirect(site_url().'/article/lister'); break;
			}
			
		//Page d'édition et d'ajout de l'article
		}else{
			$url = $this->input->post('typedUrl');
			$data["current_list"] = $this->input->post('current_list');
			//vérifications du lien entré
			if($url=='' || $url==' ' || $url=='undefined' || $url==NULL){
				redirect(site_url().'/article/lister/circle');
			}
			$url=verifierUrlFormat($url);
			if(!$this->m_article->verify($url)){
				$this->session->set_flashdata('erreur', 'Lien déjà publié');
				redirect(site_url().'/article/lister/circle');
				exit();
			}
			if(verifierUrlExiste($url)!='200'){
				$this->session->set_flashdata('erreur', 'Lien non valide');
				redirect(site_url().'/article/lister/circle');
				exit();
			}
			//récupération de page avec curl
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_USERAGENT, 'Récupération avec Curl');
			$resultat = curl_exec ($curl);
			curl_close($curl);
			//analyse des éléments du curl
			preg_match("#<title>(.*)<\/title>#i",$resultat,$titre);
			preg_match('#<meta(?=[^>]*name="description")\s[^>]*content="([^>]*)"#si',$resultat,$description);
			preg_match_all("#<img.*src=[\"|']([^\"']*)[\"|'] .*\/>#i",$resultat,$images);
			if(count($titre)){
				$data["titre"]=$titre[1];
			}else{
				$data["titre"]=$url;
			}
			if(count($description)){
				$data["description"]=$description[1];
			}else{
				$data["description"]='Aucune description';
			}
			if(count($images[1])){
				$data["images"]=traiterUrlImages($images[1],$url);
			}
			$data["url"]=$url;
			//chargement de la page
			$data["infos"]=$this->m_user->getOne($this->session->userdata('vdfbcurl_connect'));
			$dataLayout['vue']=$this->load->view('ajouter',$data, true);
			$dataLayout['titre']='Détails de l\'url';
			$this->load->view('layout', $dataLayout);
		}
	}//fin ajouter
	
	
	//---------------------------
	// Modifier un article publié
	//---------------------------
	public function modifier(){
	
		//Publication des changements si appliqués
		if($this->input->post('id')){
			$data["id"]=$this->input->post('id');
			$data["url"]=$this->input->post('url');
			$data["titre"]=$this->input->post('titre');
			if($data["titre"]=='' || $data["titre"]==' ' || $data["titre"]=='undefined' || $data["titre"]==NULL){
				$data["titre"]=$data["url"];
			}
			$data["description"]=$this->input->post('description');
			$data["partage"]=$this->input->post('partage');
			$this->m_article->update($data);
			//modification de l'image si nécessaire
			if($this->input->post('image')){
				if($this->input->post('image')!=$this->input->post('urlimg')){
					$urlimg=$this->input->post('image');
					recupererImage($urlimg,$data["id"]);
				}
			}
			//redirection en fonction du choix de partage
			switch ($data["partage"]){
				case 0 : redirect(site_url().'/article/lister/wall'); break;
				case 1 : redirect(site_url().'/article/lister/circle'); break;
				case 2 : redirect(site_url().'/article/lister'); break;
			}
			
		//Page de modification de l'article
		}else{
			$id=$this->uri->segment(3);
			$article = $this->m_article->getOne($id);
			//revérification qu'auteur correspond à article
			if($article->auteur!=$this->session->userdata('vdfbcurl_connect')){
				redirect(site_url().'/article/lister');
				exit();
			}
			//récupération du contenu de l'article
			$data['id']=$article->id;
			$url=$article->url;
			$data['url']=$url;
			$data['titre']=$article->titre;
			$data['description']=$article->description;
			$data["partage"]=$article->partage;
			//vérifier si l'image sauvegardée existe
			$filename=IMG_DIR.'article'.$id.'.jpg';
			if(file_exists('./'.$filename)){
				$data['image']=base_url().$filename;
			}
			//récupérer les images du site original
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_USERAGENT, 'Récupération avec Curl');
			$resultat = curl_exec ($curl);
			curl_close($curl);
			preg_match_all("#<img.*src=[\"|']([^\"']*)[\"|'] .*\/>#i",$resultat,$images);
			if(count($images[1])){
				$data["images"]=traiterUrlImages($images[1],$url);
			}
			//chargement de la page
			$data["infos"]=$this->m_user->getOne($this->session->userdata('vdfbcurl_connect'));
			$dataLayout['vue']=$this->load->view('modifier',$data, true);
			$dataLayout['titre']='Modification d\'article';
			$this->load->view('layout', $dataLayout);
		}
	}//fin modifier
	
	
	//----------------------------
	// Supprimer un article publié
	//----------------------------
	public function supprimer(){
	
		//Suppression de l'article si confirmé
		if($this->input->post('id')){
			$id=$this->input->post('id');
			$this->m_article->delete($id);
			//suppression de l'image
			$filename='./'.IMG_DIR.'article'.$this->input->post('id').'.jpg';
			if(file_exists($filename)){
				unlink($filename);
			}
			//redirection vers les autres publications supprimables
			redirect('article/lister/wall');
			
		//Page de confirmation de suppression
		}else{
			$id=$this->uri->segment(3);
			$article = $this->m_article->getOne($id);
			//revérification qu'auteur correspond à article
			if($article->auteur!=$this->session->userdata('vdfbcurl_connect')){
				redirect(site_url().'/article/lister');
				exit();
			}
			//récupération du contenu de l'article
			$data['id']=$id;
			$data['url']=$article->url;
			$data['titre']=$article->titre;
			$data['description']=$article->description;
			$data['partage']=$article->partage;
			$filename=IMG_DIR.'article'.$id.'.jpg';
			if(file_exists('./'.$filename)){
				$data['image']=base_url().$filename;
			}
			//chargement de la page
			$data["infos"]=$this->m_user->getOne($this->session->userdata('vdfbcurl_connect'));
			$dataLayout['vue']=$this->load->view('supprimer',$data, true);
			$dataLayout['titre']='Suppression d\'article';
			$this->load->view('layout', $dataLayout);
		}
	}//fin supprimer
}//fin Article