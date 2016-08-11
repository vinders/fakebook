<?php
if(!$this->session->userdata('vdfbcurl_connect')){
	redirect(site_url());
	exit();
}
echo form_open(site_url().'/user/supprimer',array('method'=>'post','id'=>'manager')); ?>
	<div>
		<h2>Suppression du compte</h2>
	</div>
	<p class="img_container"><?php $filename=IMG_DIR.'avatar'.$infos->auteur.'.jpg';
		if(file_exists('./'.$filename)){ ?>
			<img src="<?php echo base_url().$filename; ?>" alt="Mon avatar" />
<?php 	}else{ ?>
			<img src="<?php echo base_url().IMG_DIR.'avatar.jpg'; ?>" alt="Mon avatar" />
<?php	} ?>
	</p>
	<ul id="infos">
		<li><strong>Pseudonyme&nbsp;: </strong><?php echo $infos->pseudo; ?></li>
		<li><strong>Nom complet&nbsp;: </strong><?php echo $infos->nom; ?></li>
		<li><strong>Adresse e-mail&nbsp;: </strong><?php echo $infos->email; ?></li>
		<li>
<?php 		if($infos->cercle!='' && $infos->cercle!=NULL){ 
				$amis=count(explode(",",$infos->cercle)); if($amis==1){ echo 'Un ami'; }else{ echo $amis.' amis'; }
			}else{ ?>Aucun ami ajouté<?php } ?>
		</li>
	</ul>
<?php if(isset($erreur)){ ?>
		<p class="error"><?php echo $erreur; ?></p>
<?php } ?>
	<div>
		<label for="pass">Entrez votre mot de passe</label><input id="pass" type="password" name="pass" />
		<button type="submit">Supprimer mon compte</button><a class="btn_gray" href="<?php echo site_url(); ?>/user/voir">Annuler</a>
	</div>
<?php echo form_close(); ?>