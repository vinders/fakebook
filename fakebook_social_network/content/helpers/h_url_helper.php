<?php
//---------------------------------------------
//Helper pour URL -- Vérification et conversion
//---------------------------------------------

//--Uniformiser les url - Placement protocole
	function verifierUrlFormat($url){
		$protocol=substr($url,0,6);
		if($protocol!='http:/' && $protocol!='https:'){
			$url='http://'.$url;
		}
		if(substr($url,-1)=='/'){
			$url=substr($url,0,-1);
		}
		return $url;
	}//fin _verifierUrlFormat
	
//--Existence des url - Headers
	function verifierUrlExiste($url) {
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}//fin _verifierUrlExiste
	
//--Images articles - Traitement spécifique
	function traiterUrlImages($images,$url){
		//Récupérer le domaine et le dossier
		$beginningUrl = substr($url,0,8); 		//enlever http://# ou https:// et le garder en mémoire
		$baseUrl = substr(($url.'/'),8,-1);		//récupérer le reste pour l'explode et pour le cas où il n'y aurait aucun '/' ou aucun '.ext'
		$partsUrl = explode('/',$baseUrl);
		$numberParts = (count($partsUrl))-1;
		if($numberParts>0 && strpos($partsUrl[$numberParts],'.')){	//si '/', vérifier si fin contient '.' (fichier)
			array_pop($partsUrl);				//enlever la dernière partie si c'est un nom de fichier ou page
			$baseUrl = implode('/',$partsUrl);
		}
		$domainSlash=strpos(($baseUrl.'/'),'/');
		$domainUrl=substr($baseUrl,0,$domainSlash);
		$baseUrl=$beginningUrl.$baseUrl;		//replacer le http://# ou https:// du départ
		$domainUrl=$beginningUrl.$domainUrl;

		//Sélection d'images
		$j=0;
		$imagesAcceptees=array();
		foreach($images as $image){
			if($j<MAX_IMG){
				$mime=strtolower(substr($image,-4,4));
				if($mime=='.jpg' || $mime=='jpeg' || $mime=='.png' || $mime=='.gif' || $mime=='.bmp'){
					$image = _convertirUrlImageRelAbs($image,$baseUrl,$domainUrl);
					if(verifierUrlExiste($image)==200){
						$imagesAcceptees[$j]=$image;
						$j++;
					}
				}
			}
		}
		return $imagesAcceptees;
	}//fin _traiterUrlImages
	
//--Relatif absolu - Fonction générique
	function _convertirUrlImageRelAbs($image,$urlFolder,$domain){
		$imageUrlChar2=substr($image,0,2);
		$imageUrlChar6=substr($image,0,6);
				
		if($imageUrlChar2=='./' || $imageUrlChar2=='..'){
			//relatif ./ ou ../
			$imgtmp=$urlFolder.'/'.$image;
		}elseif($imageUrlChar6=='http:/' || $imageUrlChar6=='https:'){
			//absolu
			$imgtmp=$image;
		}elseif(substr($image,0,4)=='www.'){
			$imgtmp='http://'.$image;
		}elseif($imageUrlChar2=='//'){
			$imgtmp='http:'.$image;
		}elseif(substr($image,0,1)=='/'){
			//racine du domaine
			$imgtmp=$domain.$image;
		}elseif(strpos($image,'.') < strpos($image,'/')){ 	//on vérifie si le premier '.' est situé avant le premier '/'. Si aucun slash, il vaudra 0.
			//absolu
			$imgtmp='http://'.$image;
		}else{
			//relatif direct
			$imgtmp=$urlFolder.'/'.$image;
		}
		return $imgtmp;
	}//fin _convertirUrlImageRelAbs