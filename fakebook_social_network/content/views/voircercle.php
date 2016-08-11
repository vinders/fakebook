<?php 
if(!$this->session->userdata('vdfbcurl_connect')){
	redirect(site_url());
	exit();
}
?>
<!--[if lte IE 8]><div id="manager"><![endif]--><!--[if gt IE 8]><!--><section id="manager"><!--<![endif]-->
	<div>
		<h2>Mon cercle d&rsquo;amis<?php if(isset($amis)){ if($number!=1){ echo '<span>('.$number.' amis)</span>'; }else{ echo '<span>(1 ami)</span>'; }  } ?></h2>
		<a class="btn_gray" href="<?php echo site_url(); ?>/article/lister/circle">Voir leurs publications</a>
	</div>
<?php if(isset($amis)){ ?>
		<ul id="amis">
<?php 		$side_circle=0;
			foreach($amis as $ami){ ?>
				<li class="side_circle<?php echo $side_circle; ?>">
					<p class="thumb_container">
<?php 					$filename=IMG_DIR.'avthumb'.$ami->auteur.'.jpg';
						if(file_exists('./'.$filename)){ ?>
							<img src="<?php echo base_url().$filename; ?>" alt="Avatar de <?php echo $ami->pseudo; ?>" />
<?php					}else{ ?>
							<img src="<?php echo base_url().IMG_DIR.'avthumb.jpg'; ?>" alt="" />
<?php 					} ?>
					</p>
					<h3><?php echo '<a href="'.site_url().'/article/lister/wall/'.$ami->auteur.'" title="Voir ses publications">'.$ami->nom.'</a><span>'.$ami->pseudo.'</span>'; ?></h3>
					<div>
<?php 					if($ami->auteur!=$this->session->userdata('vdfbcurl_connect')){
							echo anchor(site_url().'/user/rompre/'.$ami->auteur,'Effacer',array('title'=>'Effacer cet ami','class'=>'btn2_gray')); 
						} ?>
					</div>
				</li>
<?php 			if($side_circle==3){$side_circle=1;}else{$side_circle++;}
			} ?>
		</ul>
<?php }else{
		echo '<p id="no_content">Aucun ami ajouté</p>';
	} ?>
<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></section><!--<![endif]-->