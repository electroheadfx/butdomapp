<?php 	!isset($ope) and $ope = '';
		!isset($action) and $action = '';

 	  echo \Theme::instance()->set_partial('header','templates/header')
 	  				->set('messages', $messages, false)
 	  				->set('title', $title, false)
 	  				->set('ope', $ope, false)
 	  				->set('action', $action, false);
?>

	<?php if (isset($page)): ?>

		<h1><?php echo $page ?></h1>
		<hr />

	<?php endif; ?>

	<?php echo $partials['content']; ?>

<?php echo \Theme::instance()->set_partial('footer','templates/footer'); ?>