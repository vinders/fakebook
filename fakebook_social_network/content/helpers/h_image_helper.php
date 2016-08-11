<?php
//----------------------------------------------------
//Helper pour images -- Vérification et enregistrement
//----------------------------------------------------

//--Image article - Conversion et échelle
	function recupererImage($urlimg,$id){

		$mime=exif_imagetype($urlimg);
		if($mime==2){
			$image = @imageCreateFromJpeg($urlimg);
		}elseif($mime==3){
			$image = @imageCreateFromPng($urlimg);
		}elseif($mime==6){
			$image = @imageCreateFromBmp($urlimg);
		}elseif($mime==1){
			$image = @imageCreateFromGif($urlimg);
        }

		if(isset($image) && $image!=NULL){ //&& $image.length>0){
			_ajouterImage($image,'article'.$id,MAX_IMG_SIZE);
			//imagedestroy($image);
        }
    }//fin _recupererImage
	
//--Image avatar - Conversion et échelle
	function recupererAvatar($id){
		$mime=exif_imagetype($_FILES['image']['tmp_name']);
		var_dump($mime);
		if($mime==2){
			$image = @imageCreateFromJpeg($_FILES['image']['tmp_name']);
		}elseif($mime==3){
			$image = @imageCreateFromPng($_FILES['image']['tmp_name']);
		}elseif($mime==6){
			$image = @imageCreateFromBmp($_FILES['image']['tmp_name']);
		}elseif($mime==1){
			$image = @imageCreateFromGif($_FILES['image']['tmp_name']);
        }
		if(isset($image) && $image!=NULL){ // && $image.length>0){
			_ajouterImage($image,'avatar'.$id,MAX_AVATAR_SIZE);
			_ajouterImage($image,'avthumb'.$id,MINI_SIZE);
			imagedestroy($image);
			$erreur=0;
		}else{
			$erreur=1;
		}
		return $erreur;
	}
	
//--Fonction générique - Taille et enregistrement
	function _ajouterImage($image,$name,$maxSize){
		$width=ImageSX($image);
		$height=ImageSY($image);
		if($width!=0 && $height!=0){
			$ratio=max(($width/$maxSize), ($height/$maxSize));
			$largeur = $width/$ratio;
			$hauteur = $height/$ratio;
			$copie=imagecreatetruecolor($largeur,$hauteur);
			imagecopyresampled($copie,$image,0,0,0,0,$largeur,$hauteur,$width,$height);
			$destination = './'.IMG_DIR.$name.'.jpg';
			imagejpeg($copie,$destination,70);
			imagedestroy($copie);
		}
    }//fin _ajouterImage