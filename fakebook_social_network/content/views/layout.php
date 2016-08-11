<!DOCTYPE HTML>
<html lang="fr-BE">
	<head>
		<meta charset="UTF-8">
		<meta	http-equiv="X-UA-Compatible"	content="IE=edge,chrome=1">
		<meta 	http-equiv="Content-Language"	content="fr" />
		<meta 	name="Author"		content="Romain Vinders" />
		<meta 	name="keywords"		content="Fakebook, curl, liens, publier des liens, publier liens" />
		<meta 	name="description"	content="Fakebook est une communauté permettant à chacun de poster des liens.">
		<meta 	name="DC.Language"	content="fr" />
		<meta 	name="DC.Creator"	content="Romain Vinders" />
		<meta 	name="DC.Date.modified"	scheme="W3CDTF" content="27/12/2012" />
		<meta 	name="viewport" 	content="width=device-width, initial-scale=1" />
		<title>Fakebook - <?php echo $titre; ?></title>
		<!--[if lte IE 8]><?php $this->load->helper('html'); echo link_tag(CSS_DIR.'style_ie8.css'); ?><![endif]-->
		<!--[if gt IE 8]><!--><?php $this->load->helper('html'); echo link_tag(CSS_DIR.'style.css'); ?><!--<![endif]-->
	</head>
	<body>
<?php	if($this->session->userdata('vdfbcurl_connect')){ ?>
			<!--[if lte IE 8]><div class="header"><![endif]--><!--[if gt IE 8]><!--><header><!--<![endif]-->
				<div id="header_container">
					<h1><a href="<?php echo site_url(); ?>" title="Accéder à la page d'accueil">Fakebook</a></h1>
					<div id="requests">
<?php 					if($this->session->userdata('requests')){
							$requests = $this->session->userdata('requests'); $count_requests=0; ?>
							<ul>
<?php							foreach($requests as $pseudo => $id){ ?>
									<li>
										<p class="thumb_container">
<?php 										$filename=IMG_DIR.'avthumb'.$id.'.jpg';
											if(file_exists('./'.$filename)){ ?>
												<img src="<?php echo base_url().$filename; ?>" alt="Avatar de <?php echo $pseudo; ?>" />
<?php 										}else{
												echo '<img src="'.base_url().IMG_DIR.'avthumb'.'.jpg'.'" alt="" />';
											} ?>
										</p>
										<a class="inviter" href="<?php echo site_url().'/article/lister/wall/'.$id; ?>"><?php echo $pseudo; ?></a>
										<a class="btn2_blue" href="<?php echo site_url(); ?>/user/confirmer_lien/lier/<?php echo $id; ?>"><span>+1 </span>Accepter</a>
										<a class="btn2_gray" href="<?php echo site_url(); ?>/user/confirmer_lien/ignorer/<?php echo $id; ?>">Ignorer</a>
										<a class="btn2_red" href="<?php echo site_url(); ?>/user/confirmer_lien/bloquer/<?php echo $id; ?>">Bloquer</a>
									</li>
<?php 								$count_requests++;
								} ?>
							</ul>
							<a href="#"><span id="count_requests"><?php echo $count_requests; ?></span><span class="icon"> demandes</span></a>
<?php					}else{ ?>
							<a href="#"><span class="icon">0 demande</span></a>
<?php					} ?>
					</div>
					<!--[if lte IE 8]><div id="nav"><![endif]--><!--[if gt IE 8]><!--><nav><!--<![endif]-->
						<a <?php if(isset($current) && $current==2){ echo 'id="currentMenu" '; } ?>href="<?php echo site_url(); ?>/article/lister"><span>Public</span></a>
						<a <?php if(isset($current) && $current==1){ echo 'id="currentMenu" '; } ?>href="<?php echo site_url(); ?>/article/lister/circle"><span>Cercle</span></a>
						<a <?php if(isset($current) && $current==0){ echo 'id="currentMenu" '; } ?>class="nav_moi" href="<?php echo site_url().'/article/lister/wall/'.$this->session->userdata('vdfbcurl_connect'); ?>"><span>Mes publications</span></a>
						<a <?php if(isset($current) && $current==6){ echo 'id="currentMenu" '; } ?>class="nav_circle" href="<?php echo site_url(); ?>/user/voircercle" title="Mes amis"><span><em>Mes amis</em></span></a>
						<a <?php if(isset($current) && $current==7){ echo 'id="currentMenu" '; } ?>class="nav_param" href="<?php echo site_url(); ?>/user/voir" title="Paramètres"><span><em>Paramètres</em></span></a>
						<a class="nav_quit" href="<?php echo site_url(); ?>/user/deconnecter" title="Se déconnecter"><span><em>Se déconnecter</em></span></a>
					<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></nav><!--<![endif]-->
				</div>
<?php	}else{ ?>
			<!--[if lte IE 8]><div id="home" class="header"><![endif]--><!--[if gt IE 8]><!--><header id="home"><!--<![endif]-->
				<h1><a href="<?php echo site_url(); ?>" title="Accéder à la page d'accueil">Fakebook</a></h1>
<?php		} ?>
		<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></header><!--<![endif]-->
		<div id="content">
<?php 		echo $vue; ?>
		</div>
		<!--[if lte IE 8]><div id="footer"><![endif]--><!--[if gt IE 8]><!--><footer><!--<![endif]-->
			<p>Fakebook 2013 - Français</p>
			<p>Toute ressemblance avec un réseau social connu est purement intentionnelle ;).</p>
		<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></footer><!--<![endif]-->
		<script src="<?php echo base_url().JS_DIR; ?>modernizr.js"></script>
<?php	if($this->session->userdata('vdfbcurl_connect')){ ?>
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
			<script>window.jQuery || document.write('<script src="<?php echo base_url().JS_DIR; ?>jquery.js"><\/script>')</script>
			<script src="<?php echo base_url().JS_DIR; ?>script.js"></script>
<?php 	} ?>
	</body>
</html>