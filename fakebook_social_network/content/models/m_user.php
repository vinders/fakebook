<?php
	class M_user extends CI_Model
	{
	//-------------------------------
	// Connexion et gestion de compte
	//-------------------------------
		//Se connecter à son compte
		function connect($pseudo,$pass){
			$pass=sha1($pass);	
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where(array('pseudo'=>$pseudo,'pass'=>$pass));
			$query = $this->db->get();
			$resultat=$query->result();
			if(empty($resultat)){
				return false;
			}else{
				return true;
			}
		}//fin connect
		
		//Récupérer un mot de passe oublié
		function recall($info,$type,$passraw){
			$pass=sha1($passraw);
			$this->db->where($type,$info);
			$this->db->update('users',array('pass'=>$pass));
			if($type='email'){
				$this->db->select('pseudo');
				$this->db->from('users');
				$this->db->where('email',$info);
				$query = $this->db->get();
				return $query->row();
			}else{
				$this->db->select('email');
				$this->db->from('users');
				$this->db->where('pseudo',$info);
				$query = $this->db->get();
				return $query->row();
			}
		}//fin recall
		
		//Créer un compte
		function add($data){
			$pass=sha1($data['pass']);
			$this->db->insert('users',array('pseudo'=>$data['pseudo'],'nom'=>$data['nom'],'email'=>$data['email'],'pass'=>$pass)); //"UNHEX('".$value."')"
		}//fin add
		
		//Supprimer son compte
		function delete($auteur){
			$this->db->delete('users', array('auteur' => $auteur)); 
			$this->db->select('*');
			$this->db->from('articles');
			$where="(auteur='".$auteur."')";
			$this->db->where($where);
			$query = $this->db->get();
			$this->db->delete('articles', array('auteur' => $auteur)); 
			return $query->result();
		}//fin delete
		
		//Modifier ses informations
		function update($data){
			$pass=sha1($data['pass']);
			$this->db->where('auteur',$data['auteur']);
			$this->db->where('pseudo',$data['pseudo']);
			$this->db->update('users',array('nom'=>$data['nom'],'email'=>$data['email'],'pass'=>$pass));
		}//fin update
		
		//Vérifier qu'un pseudo ou email est utilisé
		function verify($post,$type){
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where($type,$post);
			$query = $this->db->get();
			$resultat=$query->result();
			if(empty($resultat)){
				return false;
			}else{
				return true;
			}
		}//fin verify
		
	//------------------------------------------
	// Récupérer les informations d'une personne
	//------------------------------------------
		//Personne identifiée
		function getOne($id){
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('auteur',$id);

			$query = $this->db->get();
			return $query->row();
		}//fin see
		
		//Personne dont on connaît le nom
		function getFromName($pseudo){
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('pseudo',$pseudo);

			$query = $this->db->get();
			return $query->row();
		}//fin getId
		
	//------------------------------
	// Requêtes entre deux personnes
	//------------------------------
		//Obtenir les requêtes reliant deux personnes
		function getRequest($demandeur,$cible){
			//1 si demandeur demande, 2 si cible demande, 0 si pas présent dans la liste
			$this->db->select('*');
			$this->db->from('requests');
			$this->db->where('demandeur',$demandeur);
			$this->db->where('cible',$cible);
			$query = $this->db->get();
			$resultat=$query->result();
			if(empty($resultat)){
				$this->db->select('*');
				$this->db->from('requests');
				$this->db->where('demandeur',$cible);
				$this->db->where('cible',$demandeur);
				$query = $this->db->get();
				$resultat=$query->result();
				if(empty($resultat)){
					return 0; //non trouvé
				}else{
					return 2; //cible demande
				}
			}else{
				return 1; //demandeur demande
			}
		}//fin getRequest
		
		//Définir une nouvelle requête
		function setRequest($demandeur,$cible){
			$statut=1;
			$this->db->insert('requests',array('demandeur'=>$demandeur,'cible'=>$cible,'statut'=>$statut));
		}//fin setRequest
		
		//Supprimer une requête
		function deleteRequest($demandeur,$cible){
			$this->db->delete('requests', array('demandeur'=>$demandeur,'cible'=>$cible)); 
		}//fin deleteRequest
		
	//---------------------------------
	// Requêtes concernant une personne
	//---------------------------------
		//Obtenir toutes les requêtes ciblant quelqu'un
		function getAllRequests($cible){
			$statut=1;
			$this->db->select('*');
			$this->db->from('requests');
			$this->db->join('users', 'users.auteur = requests.demandeur', 'left');
			$this->db->where('requests.cible',$cible);
			$this->db->where('requests.statut',$statut);
			$query = $this->db->get();
			$resultat=$query->result();
			if(empty($resultat)){
				return false;
			}else{
				return $resultat;
			}
		}//fin getAllRequests
		
		//Supprimer toutes les requêtes faites par quelqu'un
		function deleteAllRequests($demandeur){
			$this->db->delete('requests', array('demandeur'=>$demandeur)); 
			$this->db->delete('requests', array('cible'=>$demandeur)); 
		}//fin deleteAllRequests
		
		//Obtenir la liste des personnes bloquées par quelqu'un
		function getBlackList($id){
			$this->db->select('demandeur');
			$this->db->from('requests');
			$this->db->where('cible',$id);
			$this->db->where('statut',0);
			$query = $this->db->get();
			$results = $query->result();
			$list=array();
			foreach($results as $result){
				array_push($list,$result->demandeur);
			}
			return $list;
		}//fin getBlackList
		
		//Bloquer une personne
		function setBlackList($demandeur,$cible){
			$statut=0; //le demandeur est bloqué
			$this->db->where('demandeur',$demandeur);
			$this->db->where('cible',$cible);
			$this->db->update('requests',array('statut'=>$statut));
		}//fin setBlackList
		
	//----------------------------------------
	// Gestion du cercle d'amis d'une personne
	//----------------------------------------
		//Ajouter une personne au cercle
		function acceptCircle($demandeur,$cible){
			$this->db->select('cercle');
			$this->db->from('users');
			$this->db->where('auteur',$demandeur);
			$query = $this->db->get();
			$result = $query->row();
			$circle = $result->cercle;
			if($circle != '' && $circle != ' '){
				$circle.=',';
			}
			$circle.=$cible;
			$this->db->where('auteur',$demandeur);
			$this->db->update('users',array('cercle'=>$circle));
		}//fin acceptCircle
		
		//Retirer une personne du cercle
		function removeCircle($demandeur,$cible){
			$this->db->select('cercle');
			$this->db->from('users');
			$this->db->where('auteur',$demandeur);
			$query = $this->db->get();
			$result = $query->row();
			$circle = $result->cercle;
			$friends = explode(",", $circle);
			$position = array_search($cible, $friends);
			if($position !== false){
				unset($friends[$position]);
			}
			$circle = implode(",", $friends);
			$this->db->where('auteur',$demandeur);
			$this->db->update('users',array('cercle'=>$circle));
		}//fin removeCircle
	}