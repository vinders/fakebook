<?php 
if(!$this->session->userdata('vdfbcurl_connect')){
	redirect(site_url());
	exit();
}
echo form_open(site_url().'/user/rompre/'.$cible,array('method'=>'post','id'=>'identity')); ?>
		<p class="personal">Utilisateur <?php echo $cible; ?></p>
		<p class="img_container">
<?php 		$filename=IMG_DIR.'avatar'.$cible.'.jpg';
			if(file_exists('./'.$filename)){ ?>
				<img src="<?php echo base_url().$filename; ?>" alt="Avatar de <?php echo $infos->pseudo; ?>" />
<?php 		}else{ ?>
				<img src="<?php echo base_url().IMG_DIR.'avatar.jpg'; ?>" alt="" />
<?php		} ?>
			<input type="hidden" name="cible" value="<?php echo $cible; ?>" />
		</p>
		<h2><?php echo $infos->nom.'<span>'.$infos->pseudo.'</span>'; ?></h2>
		<a class="btn_gray rightspace" href="<?php echo site_url().'/article/lister/wall/'.$cible; ?>">Annuler</a>
		<button class="btn_red" type="submit">Effacer de mes amis</button>
<?php echo form_close(); ?>