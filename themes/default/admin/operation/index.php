<h2>Listing <span class='muted'> Jeux</span></h2>
<br>
<?php if ($butdom_operations): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Département</th>
			<th>Titre</th>
			<th>Event</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($butdom_operations as $item): ?>		<tr>

			<td><?php echo $item->departement; ?></td>
			<td><?php echo $item->title; ?></td>
			<td><?php echo $item->event; ?></td>
			<td>
				<div class="btn-toolbar">
					<div class="btn-group">
						<?php echo Html::anchor('admin/operation/view/'.$item->id, '<i class="glyphicon glyphicon-eye-open"></i> Voir', array('class' => 'btn btn-default btn-sm')); ?>
						<?php if(\Auth::has_access('admin.list[modify]')): ?>
							<?php echo Html::anchor('admin/operation/edit/'.$item->id, '<i class="glyphicon glyphicon-wrench"></i> Editer', array('class' => 'btn btn-default btn-sm')); ?>
							<?php echo Html::anchor('admin/operation/delete/'.$item->id, '<i class="glyphicon glyphicon-trash"></i> Effacer', array('class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')")); ?>
						<?php endif; ?>
					</div>
				</div>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Butdom_operations.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/operation/create', 'Ajouter une opération', array('class' => 'btn btn-success')); ?>

</p>
