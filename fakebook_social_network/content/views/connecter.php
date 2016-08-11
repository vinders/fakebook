<?php echo form_open(site_url().'/user/connecter',array('method'=>'post','id'=>'connect_form')); ?>
	<div class="connect_div">
		<label class="max" for="login">Login utilisateur</label><input id="login" type="text" tabindex=1 name="login" />
		<input id="remember" type="checkbox" tabindex=3 name="remember" /><label class="down" for="remember">Rester connecté</label>
	</div>
	<div class="connect_div">
		<label class="max" for="pass">Mot de passe</label><input id="pass" type="password" tabindex=2 name="pass" />
		<a class="down" href="<?php echo site_url().'/user/recuperer/#manager'; ?>">Mot de passe oublié&nbsp;?</a>
	</div>
	<div class="connect_end">
		<p class="error"><?php echo $erreurConnexion; ?></p>
		<button type="submit" tabindex=4>Connexion</button>
	</div>
<?php echo form_close(); ?>
<!--[if lte IE 8]><div id="banner"><![endif]--><!--[if gt IE 8]><!--><section id="banner"><!--<![endif]-->
<?php if(isset($erreurInscription) && $erreurInscription=='ok'){
		echo '<h2>Bienvenue. Vous pouvez vous connecter ci-dessus.</h2>';
	}else{ ?>
		<h2>Fakebook vous permet de publier une liste de liens et leur résumé pour les partager.</h2>
<?php } ?>
<!--[if lte IE 8]></div><![endif]--><!--[if gt IE 8]><!--></section><!--<![endif]-->
<?php echo form_open(site_url().'/user/ajouter',array('method'=>'post','id'=>'subscribe_form')); ?>
	<h2>Inscrivez-vous ici</h2>
<?php if($this->session->flashdata('desinscription') && $this->session->flashdata('desinscription')!=''){
		echo '<p class="success">'.$this->session->flashdata('desinscription').'</p>';
	} 
	$verif1=rand(0,32);
	$verif2=rand(0,32);
	$verif=$verif1+$verif2;
	if(isset($erreurInscription) && $erreurInscription=='ok'){
		echo '<p class="success">Votre inscription a été effectuée avec succès.</p>';
	}else{ ?>
		<ul>
			<li><label for="pseudo">Votre pseudo&nbsp;:</label><input id="pseudo" type="text" name="pseudo" value="<?php if(isset($pseudo)){ echo $pseudo; } ?>" /></li>
			<li><label for="nom">Votre nom&nbsp;:</label><input id="nom" type="text" name="nom" value="<?php if(isset($nom)){ echo $nom; } ?>" /></li>
			<li><label for="email">Votre email&nbsp;:</label><input id="email" type="text" name="email" value="<?php if(isset($email)){ echo $email; } ?>" /></li>
			<li><label for="addpass">Mot de passe&nbsp;:</label><input id="addpass" type="password" name="addpass" value="<?php if(isset($pass)){ echo $pass; } ?>" /></li>
			<li><label for="addpass2">Confirmez-le&nbsp;:</label><input id="addpass2" type="password" name="addpass2" value="<?php if(isset($pass2)){ echo $pass2; } ?>" /></li>
			<li><label for="verif">Résultat <?php echo $verif1.'+'.$verif2;?>&nbsp;:</label><input id="verif" type="text" name="verif" /></li>
		</ul>
		<p class="error"><?php if(isset($erreurInscription)){ echo $erreurInscription; } ?><input type="hidden" name="verifok" value="<?php echo $verif; ?>" /></p>
		<button type="submit">Inscription</button>
<?php }
echo form_close(); ?>
<script src="<?php echo base_url().JS_DIR; ?>subscribe.js"></script>