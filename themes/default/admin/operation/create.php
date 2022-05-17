<h2>Créer un nouveau jeu</h2>
<br>
<?php echo \Theme::instance()->view('admin/operation/_form')->set('departements', $departements)->render(); ?>

<p><?php echo Html::anchor('admin/operation', 'Retour'); ?></p>
