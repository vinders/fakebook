<?php 
if(!$this->session->userdata('vdfbcurl_connect')){
	redirect(site_url());
	exit();
}
?>
<!--[if lte IE 8]><div id="manager"><![endif]--><!--[if gt IE 8]><!--><section id="manager"><!--<![endif]-->
	<div>
		<h2>Paramètres généraux</h2>
		<a class="btn_gray" href="<?php echo site_url(); ?>/user/supprimer">Supprimer mon compte</a>
		<a class="btn_gray" href="<?php echo site_url(); ?>/user/modifier">Modifier mes informations</a>
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
<?php 		if($infos->cercle!='' && $infos->cercle!=NULL){ ?>
				<a href=<?php echo '"'.site_url().'/user/voircercle">'; $amis=count(explode(",",$infos->cercle)); if($amis==1){ echo 'Un ami'; }else{ echo $amis.' amis'; } ?></a>
<?php 		}else{ ?>Aucun ami ajouté<?php } ?>
		</li>
	</ul>
<?php if($this->session->flashdata('erreur_img') && $this->session->flashdata('erreur_img')!=''){ ?>
		<p class="error"><?php echo $this->session->flashdata('erreur_img'); ?></p>
<?php } ?>
<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></section><!--<![endif]-->