<h2>Edition du <span class='muted'> jeu</span></h2>
<br>

<?php echo \Theme::instance()->view('admin/operation/_form')->set('departements', $departements)->render(); ?>
<p>

<div class="group-separator">
	<div class="btn-group">
	  <a type="button" class="btn btn-default" href="/admin/operation"><span class="glyphicon glyphicon-share-alt"></span> Retour</a>
	  <a type="button" class="btn btn-default" href="/admin/operation/view/<?php echo $butdom_operation->id ?>"><span class="glyphicon glyphicon-eye-open"></span> Voir</a>
	</div>
</div>