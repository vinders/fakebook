<?php
if(!$this->session->userdata('vdfbcurl_connect')){
	redirect(site_url());
	exit();
}
echo form_open_multipart(site_url().'/user/modifier',array('method'=>'post','id'=>'manager')); ?>
	<div>
		<h2>Modification des paramètres</h2>
	</div>
	<fieldset>
<?php 	$filename=IMG_DIR.'avatar'.$infos->auteur.'.jpg'; ?>
		<h3>Charger une image (jpeg/png/gif)</h3>
		<input type="file" name="image" class="file"  />
		<input type="hidden" name="MAX_FILE_SIZE" value="1024K" />
		<input type="hidden" name="pseudo" value="<?php echo $infos->pseudo; ?>" />
<?php	if(file_exists('./'.$filename)){ ?>
			<img src="<?php echo base_url().$filename; ?>" alt="Mon avatar" />
<?php 	}else{ ?>
			<img src="<?php echo base_url().IMG_DIR.'avatar.jpg'; ?>" alt="Mon avatar actuel" />
<?php	} ?>
	</fieldset>
	<ul id="infos" class="param_user">
		<li><strong>Pseudonyme&nbsp;: </strong><?php echo $infos->pseudo; ?></li>
		<li><label for="nom">Nom complet&nbsp;: </label><input id="nom" type="text" name="nom" value="<?php echo $infos->nom; ?>" /></li>
		<li><label for="email">Adresse e-mail&nbsp;: </label><input id="email" type="text" name="email" value="<?php echo $infos->email; ?>" /></li>
		<li><label for="newpass">Nouveau mot de passe*&nbsp;: </label><input id="newpass" type="password" name="newpass" /></li>
		<li><label for="newpass2">Confirmer mot de passe*&nbsp;:</label><input id="newpass2" type="password" name="newpass2" /></li>
		<li><em>*champs optionnels</em></li>
	</ul>
	<p class="error">
<?php 	if(isset($erreur)){
			echo $erreur;
		} 
		if($this->session->flashdata('erreur_img') && $this->session->flashdata('erreur_img')!=''){ 
			 echo ' '.$this->session->flashdata('erreur_img');
		} ?>
	</p>
	<div>
		<label for="pass">Votre mot de passe actuel&nbsp;:</label><input id="pass" type="password" name="pass" />
		<button type="submit">Enregistrer les modifications</button><a class="btn_gray" href="<?php echo site_url(); ?>/user/voir">Annuler</a>
	</div>
<?php echo form_close(); ?>