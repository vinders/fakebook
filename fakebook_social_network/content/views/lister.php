<?php 
if(!$this->session->userdata('vdfbcurl_connect')){
	redirect(site_url());
	exit();
} 
//Profil de présentation de la personne concernée par la page
if($current==0 || $current==5 || $current==4){ ?>
	<!--[if lte IE 8]><div id="identity"><![endif]--><!--[if gt IE 8]><!--><section id="identity"><!--<![endif]-->
		<p class="personal">Utilisateur <?php echo $current_id; ?></p>
		<p class="img_container"><?php $filename=IMG_DIR.'avatar'.$current_id.'.jpg';
			if(file_exists('./'.$filename)){ ?>
				<img src="<?php echo base_url().$filename.'" alt="Avatar de '.$current_pseudo; ?>" />
<?php 		}else{ ?>
				<img src="<?php echo base_url().IMG_DIR.'avatar.jpg'; ?>" alt="" />
<?php		} ?>
		</p>
		<h2><?php if($current!=5){ echo $current_nom.'<span>'.$current_pseudo.'</span>'; }else{ echo $current_pseudo; } ?>
<?php	if($current==0){ ?>
			</h2><a class="btn_gray rightspace" href="<?php echo site_url(); ?>/user/deconnecter" title="Se déconnecter">Se déconnecter</a>
<?php	}elseif($current==4){ ?>
			</h2><a class="btn_gray rightspace" href="<?php echo site_url().'/user/rompre/'.$current_id; ?>">Effacer de mes amis</a>
<?php	}elseif($current_status==1){ ?>
			<span>Invitation reçue.</span></h2>
			<a class="btn_red rightspace" href="<?php echo site_url().'/user/confirmer_lien/bloquer/'.$current_id; ?>">Bloquer</a>
			<a class="btn_gray" href="<?php echo site_url().'/user/confirmer_lien/ignorer/'.$current_id; ?>">Ignorer</a>
			<a class="btn_blue" href="<?php echo site_url().'/user/confirmer_lien/lier/'.$current_id; ?>"><span>+1 </span>Accepter</a>
<?php	}elseif($current_status==3){ ?>
			<span>Utilisateur bloqué.</span></h2>
			<a class="btn_gray rightspace" href="<?php echo site_url().'/user/confirmer_lien/lier/'.$current_id; ?>">Débloquer et accepter</a>
<?php	}elseif($current_status==2){ ?>
			<span>Demande en attente.</span></h2>
<?php	}else{ ?>
			</h2><a class="btn_gray rightspace" href="<?php echo site_url().'/user/demander/'.$current_id; ?>">Ajouter aux amis</a>
<?php	} ?>
	<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></section><!--<![endif]--><?php
}
//Formulaire de publication
if($current!='4' && $current!='5'){
	echo form_open(site_url().'/article/ajouter',array('method'=>'post','id'=>'curl')); ?>
		<h2><span>Publication&nbsp;: </span>Adresse URL de la page à publier</h2>
		<div class="toolbar">
			<!--<input type="text" name="typedUrl" placeholder="http://" />-->
			<textarea name="typedUrl" rows="2" cols="50" placeholder="http://"></textarea><input type="hidden" name="current_list" value="<?php echo $current; ?>" />
<?php 		if($this->session->flashdata('erreur') && $this->session->flashdata('erreur')!=''){ ?>
				<p class="error"><?php echo $this->session->flashdata('erreur'); ?></p>
<?php 		} ?>
			<button type="submit">Publier</button>
		</div>
<?php echo form_close(); 
} 
//Articles correspondant au critère de la page
if(count($articles)==0){
	echo '<p id="no_content">Aucune publication</p>';
}else{
	$count=$side=0;
	foreach($articles as $article){ ?>
		<div class="side<?php echo $side; ?>">
			<span class="nb">N° <?php echo $count; ?></span>
			<!--[if lte IE 8]><div class="publication"><![endif]--><!--[if gt IE 8]><!--><article class="publication"><!--<![endif]-->
				<div class="post_top">
					<p class="thumb_container">
<?php 					$filename=IMG_DIR.'avthumb'.$article->auteur.'.jpg';
						if(file_exists('./'.$filename)){ ?>
							<img src="<?php echo base_url().$filename; ?>" alt="" />
<?php 					}else{
							echo '<img src="'.base_url().IMG_DIR.'avthumb.jpg" alt="" />';
						} ?>
					</p>
					<h4><a href="<?php echo site_url().'/article/lister/wall/'.$article->auteur; ?>"><?php echo $article->pseudo; ?></a></h4>
					<p>
						<?php echo $article->date; ?>
<?php 					if($article->auteur==$this->session->userdata('vdfbcurl_connect')){
							switch ($article->partage) {
								case 0:
									echo " (Privé)";break;
								case 1:
									echo " (Amis)";break;
								case 2:
									echo " (Public)";break;
							} ?>
					</p>
						<ul>
							<li><?php echo anchor(site_url().'/article/modifier/'.$article->id,'<span class="mod_btn">Modifier</span>',array('title'=>'Modifier la publication','class'=>'btn_modif')); ?></li><li><?php echo anchor(site_url().'/article/supprimer/'.$article->id,'<span class="del_btn">Supprimer</span>',array('title'=>'Effacer la publication','class'=>'btn_suppr')); ?></li>
						</ul>
<?php				}else{
						echo '</p>';
					}?>
				</div>
				<div class="post_bottom">
<?php 				$filename=IMG_DIR.'article'.$article->id.'.jpg';
					if(file_exists('./'.$filename)){ ?>
						<p class="img_container"><img src="<?php echo base_url().$filename; ?>" alt="" /></p>
						<h3><a href="<?php echo $article->url; ?>"><?php echo $article->titre; ?></a></h3>
						<p><?php echo $article->description; ?></p>
<?php				}else{ ?>
						<h3 class="no_pic"><a href="<?php echo $article->url; ?>"><?php echo $article->titre; ?></a></h3>
						<p class="no_pic"><?php echo $article->description; ?></p>
<?php				} ?>
				</div>
			<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></article><!--<![endif]-->
		</div> 
<?php	if($side==0){ $side=1; }else{ $side=0; }
		$count++;
	}
} ?>