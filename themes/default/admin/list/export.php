<div>
	<h1><?php echo $title; ?></h1>
	<h2><?php echo $event; ?></h2>
	<p class="title">Bulletin de participation <?php echo isset($parrain) ? "(parrainage N°$parrain)" : '(inscription)'; ?> :</p>
	
	<div class="content">
		<?php if(!empty($name)): ?>
			<p>Nom : <span><?php echo $name ?></span>
		<?php endif; ?>
		
		<?php if(!empty($surname)): ?>
			<span class="tiret">—</span>
			Prénom : <span><?php echo $surname ?></span></p>
		<?php endif; ?>
		
		<?php if(!empty($email)): ?>
			<p>E-mail : <span><?php echo $email ?></span>
		<?php endif; ?>

		<?php if(!empty($phone)): ?>
			<span class="tiret">—</span>
			Téléphone : <span><?php echo $phone ?></span></p>
		<?php endif; ?>
	</div>

</div>
<hr/>