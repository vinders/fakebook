<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	//chargement du modèle et des helpers
	public function __construct(){
		parent::__construct();
		$this->load->model('m_article');
		$this->load->model('m_user');
	}
	
	public function supprimer(){
		$id = $this->uri->segment(3);
		$this->m_article->delete($id);
		//suppression de l'image
		$filename='./'.IMG_DIR.'article'.$id.'.jpg';
		if(file_exists($filename)){
			unlink($filename);
		}
	}//fin supprimer
	
	function rompre(){
		$demandeur = $this->session->userdata('vdfbcurl_connect');
		$cible = $this->uri->segment(3);
		$this->m_user->removeCircle($demandeur,$cible);
		$this->m_user->removeCircle($cible,$demandeur);
	}//fin rompre
	
	function lier(){
		$cible = $this->session->userdata('vdfbcurl_connect');
		$demandeur = $this->uri->segment(3);
		
		if($this->m_user->getRequest($demandeur,$cible)==1){

			$blacklist=array_search($demandeur, $this->session->userdata('blacklist'));
			if($blacklist !== false){
				$userdata = $this->session->userdata('blacklist');
				unset($userdata[$blacklist]);
				$this->session->set_userdata('blacklist',$userdata);
			}
			$this->m_user->acceptCircle($demandeur,$cible);
			$this->m_user->acceptCircle($cible,$demandeur);
			$this->m_user->deleteRequest($demandeur,$cible);
			
		}
	}
	function ignorer(){
		$cible = $this->session->userdata('vdfbcurl_connect');
		$demandeur = $this->uri->segment(3);
		if($this->m_user->getRequest($demandeur,$cible)==1){
			$this->m_user->deleteRequest($demandeur,$cible);
		}
	}
	function bloquer(){
		$cible = $this->session->userdata('vdfbcurl_connect');
		$demandeur = $this->uri->segment(3);
			
		if($this->m_user->getRequest($demandeur,$cible)==1){
			if(!in_array($demandeur,$this->session->userdata('blacklist'))){
				$blacklist=$this->session->userdata('blacklist');
				array_push($blacklist,$demandeur);
				$this->session->set_userdata('blacklist',$blacklist);
			}
			$this->m_user->setBlackList($demandeur,$cible);
		}
	}
}//fin Article