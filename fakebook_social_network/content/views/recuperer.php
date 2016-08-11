<?php echo form_open(site_url().'/user/connecter',array('method'=>'post','id'=>'connect_form')); ?>
	<div class="connect_div">
		<label class="max" for="login">Login utilisateur</label><input id="login" type="text" tabindex=1 name="login" />
		<input id="remember" type="checkbox" tabindex=3 name="remember" /><label class="down" for="remember">Rester connecté</label>
	</div>
	<div class="connect_div">
		<label class="max" for="pass">Mot de passe</label><input id="pass" type="password" tabindex=2 name="pass" />
		<a class="down" href="#manager">Mot de passe oublié&nbsp;?</a>
	</div>
	<div class="connect_end">
		<p class="error"></p>
		<button type="submit" tabindex=4>Connexion</button>
	</div>
<?php echo form_close(); 
if(isset($message) && $message=='ok'){
	echo '<p class="success">Un email comprenant votre identifiant et un nouveau mot de passe a été envoyé.</p>';
}else{
	echo form_open(site_url().'/user/recuperer',array('method'=>'post','id'=>'manager','class'=>'recup_form')); ?>
	<div><h2>Identifier votre compte</h2></div>
	<p>Veuillez entrer ci-dessous votre pseudonyme ou votre adresse e-mail.</p>
<?php $verif1=rand(0,32);
	$verif2=rand(0,32);
	$verif=$verif1+$verif2; ?>
	<p class="error"><?php if(isset($message)){ echo $message; } ?></p>
	<div>
		<label for="info">Pseudo ou email</label><input id="info" type="text" name="info" value="<?php if(isset($info)){ echo $info; } ?>" />
		<label for="verif">Résultat de <?php echo $verif1.'+'.$verif2;?></label><input id="verif" type="text" name="verif" />
		<input type="hidden" name="verifok" value="<?php echo $verif; ?>" />
		<button type="submit">Envoyer</button>
	</div>
<?php echo form_close(); } ?>