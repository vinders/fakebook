<?php
if(!$this->session->userdata('vdfbcurl_connect')){
	redirect(site_url());
	exit();
}
?>
<!--[if lte IE 8]><div id="identity"><![endif]--><!--[if gt IE 8]><!--><section id="identity"><!--<![endif]-->
	<p class="personal">Utilisateur <?php echo $infos->auteur; ?></p>
	<p class="img_container"><?php $filename=IMG_DIR.'avatar'.$infos->auteur.'.jpg';
		if(file_exists('./'.$filename)){ ?>
			<img src="<?php echo base_url().$filename; ?>" alt="Mon avatar" />
<?php 	}else{ ?>
			<img src="<?php echo base_url().IMG_DIR.'avatar.jpg'; ?>" alt="Mon avatar" />
<?php	} ?>
	</p>
	<h2><?php echo $infos->nom.'<span>'.$infos->pseudo.'</span>'; ?></h2>
	<a class="btn_gray rightspace" href="<?php echo site_url(); ?>/user/deconnecter" title="Se déconnecter">Se déconnecter</a>
<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></section><!--<![endif]-->


<?php
 echo form_open(site_url().'/article/supprimer',array('method'=>'post','id'=>'curl')); ?>
	<h2><span>Publication&nbsp;: </span>Supprimer la publication</h2>
	<div class="toolbar">
		<p id="curl_url"><?php echo $url; ?></p>
		<a href="<?php echo site_url().'/article/lister/wall/'.$infos->auteur; ?>">Annuler</a>
		<button type="submit">Supprimer</button>
	</div>
	<div class="curl_content">
<?php 	if(isset($image)){ ?>
			<p class="img_container">
				<img src="<?php echo $image; ?>" alt="" />
			</p>
<?php	} ?>
		<h3><?php echo $titre; ?></h3>
		<p><?php echo $description; ?></p>
		<input type="hidden" name="id" value="<?php echo $id; ?>">
	</div>
<?php echo form_close(); ?>