
<?php if($submit): ?>

	<a href="/admin/import/index/" class="btn btn-default" data-dismiss="modal">Importer une nouvelle liste</a>
	<?php echo $content ?>

<?php else: ?>

	<h1><?php echo $title ?></h1>

	<?php if(!empty($dirContent)): ?>

		<div>

			<hr/>

			<p><i>Déterminez le type de status à appliquer sur la liste :</i></p>

			<div class="group-separator confirmed">
				<div class="btn-group">
					<a href="/admin/import/index/approved" class="btn btn-<?php echo $confirmed == 'approved' ? $state : 'default '.$state ?> btn-sm"><?php echo $status['approved'] ?></a>
					<a href="/admin/import/index/pending" class="btn btn-<?php echo $confirmed == 'pending'  ? $state : 'default '.$state ?> btn-sm"><?php echo $status['pending'] ?></a>			
					<a href="/admin/import/index/refused" class="btn btn-<?php echo $confirmed == 'refused'  ? $state : 'default '.$state ?> btn-sm"><?php echo $status['refused'] ?></a>
				</div>
			</div>

			<br/>

			<p><i>Choisissez un fichier parmis cette liste :</i></p>

			<?php echo \Form::open() ?>
			
				<div id="listfile" class="btn-group-vertical" data-toggle="buttons">
					<?php 
					foreach ($dirContent as $file) {
						echo '<label class="btn btn-default render" >';
						echo '<input type="radio" value="'.$file.'" >';
						echo "$file";
						echo '</label>';
					}
					?>
				 </div>
				
				<hr/>

				<div>
					<input name="render" id="render" type="hidden" value="">
					<button id="import" data-toggle="modal" data-target="#importlist" class="btn btn-danger">Go !</button>
				</div>

				<!-- bootstrap modal for import list -->
				<div class="modal fade" id="importlist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        <h4 class="modal-title" id="myModalLabel">Importer une liste</h4>
				      </div>
				      <div class="modal-body">
				        <p class="import-warning"><strong>Attention !</strong> Voulez-vous vraiment mettre à jour la base de donnée avec avec la liste choisie ? avez-vous fait un backup avant ?</p>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
				        <input id="confirm" type="submit" class="btn btn-danger" value="Go !" >

				      </div>
				    </div>
				  </div>
				</div>
				<!-- end bootstrap import list -->

			<?php echo \Form::close() ?>	

		</div>

	<?php else: ?>
		
		<div>
				<p>Aucune liste existante pour le moment.</p>
				<p>Importez en une ou plusieurs dans le dossier : <b>public/upload/clean_lists/</b></p>
		</div>

	<?php endif; ?>

<?php endif; ?>
