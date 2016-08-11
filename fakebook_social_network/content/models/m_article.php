<?php
	class M_article extends CI_Model
	{
	//----------------------
	// Liste de publications
	//----------------------
		//Toutes les publications publiques
		function listAll(){
			$this->db->select('*');
			$this->db->from('articles');
			$this->db->join('users', 'users.auteur = articles.auteur', 'left');
			$this->db->where('partage','2');
			$this->db->order_by("id", "desc"); 
			$query = $this->db->get();
			return $query->result();
		}//fin listAll
		
		//Publications du cercle d'amis
		function listCircle($auteur,$cercle){
			$this->db->select('*');
			$this->db->from('articles');
			$this->db->join('users', 'users.auteur = articles.auteur', 'left');
			$where="(partage='1' OR partage='2') AND (articles.auteur='".$auteur."'";
			if($cercle[0]!=''){
				foreach($cercle as $ami){
					$where=$where." OR articles.auteur='".$ami."'";
				}
			}
			$where.=")";
			$this->db->where($where);
			$this->db->order_by("id", "desc"); 
			$query = $this->db->get();
			return $query->result();
		}//fin listCircle
		
		//Publications d'une seule personne
		function listOne($auteur,$partage){
			$this->db->select('*');
			$this->db->from('articles');
			$this->db->join('users', 'users.auteur = articles.auteur', 'left');
			$where="(articles.auteur='".$auteur."')";
			if($partage==1){
				$where.=" AND (partage='1' OR partage='2')";
			}
			elseif($partage==2){
				$where.=" AND partage='2'";
			}
			$this->db->where($where);
			$this->db->order_by("id", "desc"); 
			$query = $this->db->get();
			return $query->result();
		}//fin listOne
		
	//----------------------
	// Une seule publication
	//----------------------
		//Publication identifiée
		function getOne($id){
			$this->db->select('*');
			$this->db->from('articles');
			$this->db->where('id',$id);
			$query = $this->db->get();
			return $query->row();
		}//fin getOne
		
		//Dernière publication
		function getLast(){
			$this->db->select('*');
			$this->db->from('articles');
			$query = $this->db->get();
			$resultat=$query->result();
			$resultat=end($resultat);
			return $resultat;
		}//fin getLast
		
	//---------------------
	// Gestion des articles
	//---------------------
		//Ajouter un article
		function add($data){
			$this->db->insert('articles',array('auteur'=>$data['auteur'],'url'=>$data['url'],'titre'=>$data['titre'],'description'=>$data['description'],'date'=>$data['date'],'partage'=>$data['partage']));
		}//fin add
		
		//Supprimer un article
		function delete($id){
			$this->db->delete('articles', array('id' => $id)); 
		}//fin delete
		
		//Modifier un article
		function update($data){
			$this->db->where('id',$data['id']);
			$this->db->update('articles',array('url'=>$data['url'],'titre'=>$data['titre'],'description'=>$data['description'],'partage'=>$data['partage']));
		}//fin update
		
		//Vérifier si un lien est déjà posté
		function verify($url){
			$auteur=$this->session->userdata('vdfbcurl_connect');
			$this->db->select('*');
			$this->db->from('articles');
			$this->db->where('url',$url);
			$this->db->where('auteur',$auteur);
			$query = $this->db->get();
			$resultat=$query->result();
			if(empty($resultat)){
				return true;
			}else{
				return false;
			}
		}//fin verify
	}