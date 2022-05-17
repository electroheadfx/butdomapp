
<div class="loto">

	<div class="group-separator departement">
		<span><a href="/admin/clients/all/<?php echo $action ?>" 		name="departement" type="button" class="btn <?php echo $departement == 'all' ? 'btn-primary' : 'btn-default' ?> btn" >Tous</a></span>
		<span class="btn-group">
			<a href="/admin/clients/guadeloupe/<?php echo $action ?>" 	name="departement" type="button" class="btn <?php echo $departement == 'guadeloupe' ? 'btn-primary' : 'btn-default' ?> btn" >Guadeloupe</a>
			<a href="/admin/clients/martinique/<?php echo $action ?>" 	name="departement" type="button" class="btn <?php echo $departement == 'martinique' ? 'btn-primary' : 'btn-default' ?> btn" >Martinique</a>
			<a href="/admin/clients/guyane/<?php echo $action ?>" 		name="departement" type="button" class="btn <?php echo $departement == 'guyane' ? 'btn-primary' : 'btn-default' ?> btn" >Guyane</a>
			<a href="/admin/clients/reunion/<?php echo $action ?>" 		name="departement" type="button" class="btn <?php echo $departement == 'reunion' ? 'btn-primary' : 'btn-default' ?> btn" >Réunion</a>
			<a href="/admin/clients/stmartin/<?php echo $action ?>"		name="departement" type="button" class="btn <?php echo $departement == 'stmartin' ? 'btn-primary' : 'btn-default' ?> btn" >Saint Martin</a>
		</span>
	</div>

	<?php echo \Form::open(array('class' => 'form-inline bar-options')); ?>

		<div class="group-separator search-area pull-right">

			<div class="input-group btn-group">

				<input id="search" type="text" class="form-control input-sm" name="search" value="<?php echo $search ?>" placeholder="Recherche ...">

				<div class="input-group-btn">

					<button type="submit" id="filter" name="filter" value="<?php echo $filter ?>" class="btn btn-<?php echo $state ?> btn-sm search" tabindex="-1" >Filtrer par <?php echo $filter ?></button>
					<button type="button" class="btn btn-<?php echo $state ?> btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1" > <span class="caret"></span> </button>
					
					<ul class="dropdown-menu" role="filter-mod">
						<li><a class="filter-mod" value="name" href="<?php echo \Uri::base(false).'admin/clients/'.$departement.'/'.$action ?>">par nom</a></li>
						<li><a class="filter-mod" value="email" href="<?php echo \Uri::base(false).'admin/clients/'.$departement.'/'.$action ?>">par email</a></li>
					</ul>

				</div>

			</div>

		</div>

		<div class="group-separator confirmed">
			<div class="btn-group">
				<button name="confirmed" type="submit" value="all" 	class="btn btn-<?php echo $confirmed == 'all' ? $state : 'default '.$state ?> btn-sm"><?php echo $status['all'] ?></button>
				<button name="confirmed" type="submit" value="approved" 	class="btn btn-<?php echo $confirmed == 'approved' ? $state : 'default '.$state ?> btn-sm"><?php echo $status['approved'] ?></button>
				<button name="confirmed" type="submit" value="pending" 	class="btn btn-<?php echo $confirmed == 'pending'  ? $state : 'default '.$state ?> btn-sm"><?php echo $status['pending'] ?></button>
				<button name="confirmed" type="submit" value="refused" 	class="btn btn-<?php echo $confirmed == 'refused'  ? $state : 'default '.$state ?> btn-sm"><?php echo $status['refused'] ?></button>
			</div>
		</div>

	<?php if ($action == "loto"): ?>
	
		<div class="group-separator loto-area btn-group">
			<input type="text"  name="startdate" class="form-control datepicker btn btn-default btn-sm" value="<?php echo $startdate ?>" data-trigger="keyup focusin focusout change" data-regexp="^(3[01]|[12][0-9]|0[1-9])/(1[0-2]|0[1-9])/[0-9]{4}$" data-validation-minlength="0" data-error-message="Entrez la date de début (JJ/MM/AAAA)" placeholder="Date début" data-required="true" data-error-container=".dateinvoice.errorcontainer" data-date-format="dd/mm/yy" >
			<input type="text"  name="enddate" class="form-control datepicker btn btn-default btn-sm" value="<?php echo $enddate ?>" data-trigger="keyup focusin focusout change" data-regexp="^(3[01]|[12][0-9]|0[1-9])/(1[0-2]|0[1-9])/[0-9]{4}$" data-validation-minlength="0" data-error-message="Entrez la date de fin (JJ/MM/AAAA)" placeholder="Date fin" data-required="true" data-error-container=".dateinvoice.errorcontainer" data-date-format="dd/mm/yy" >
			<button name="ramdon" type="submit" value="true" class="btn btn-default btn-sm loterie">Tirage au sort</button>
		</div>
	
	<?php elseif ($action == "operation" || $action == "operation_closed" ):?>
		
		<?php if ($operation_id > 0): ?>
			<div class="alert alert-success alert-dismissable">
				<a type="button" class="close pull-left" href="<?php echo \Uri::base(false).'admin/clients/'.$departement.'/operation_closed' ?>">&times;</a>
				Inscriptions opération "<strong><?php echo ucfirst($operation_name) ?></strong>" du <?php echo $operation_date ?> :
			</div>
		<?php else: ?>
			<div class="input-group btn-group">

				<div class="btn-group">
					<button type="button" id="operation" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
						Choisissez une opération web
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<?php foreach ($operations as $operation): ?>
							<li><a href="<?php echo \Uri::base(false).'admin/clients/'.$departement.'/operation' ?>?id=<?php echo $operation->id ?>">"<?php echo ucfirst($operation->name) ?>" - <?php echo ucfirst($operation->departement) ?> du <?php echo \Date::forge($operation->created_at)->format("%d/%m/%Y") ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>

			</div>
		<?php endif; ?>

	<?php endif; ?>
		
		<div class="dateinvoice errorcontainer" ></div>


	<?php echo \Form::close(); ?>

</div>


<?php if (isset($clients)): ?>

	<?php $dep=""; ?>
	
	<h5 class="departement <?php echo $confirmed ?>" >

		<?php echo $departement != 'all' ? ucfirst($departement).' <span class="badge '.$confirmed.'" >' . $total . '</span>' : 'Tous > <span class="badge '.$confirmed.'" >' . $total . '</span>' ?>
		
		<?php if(\Auth::has_access('admin.list[delete]')): ?>
			&nbsp;<a class="btn btn-default btn-xs" data-toggle="modal" href="<?php echo \Uri::base(false) ?>admin/export/index/<?php echo $departement ?>/<?php echo $confirmed ?>/<?php echo $operation_id ?>" >Export</a>
		<?php endif; ?>
		<?php if($operation_id > 0): ?>
			&nbsp;<a class="btn btn-default btn-xs" data-toggle="modal" href="<?php echo \Uri::base(false) ?>admin/export/print/<?php echo $departement ?>/<?php echo $confirmed ?>/<?php echo $operation_id ?>" target="_blank" >Print</a>
		<?php endif; ?>

	</h5>
	
	<?php if ($departement != 'all') echo '<hr class="sepdep" />' ?>

	<?php foreach ($clients as $client): ?>
		
		<?php if ($dep != $client->departement) {

					echo $departement == 'all' ? '<h5 class="departement '.$confirmed.'" >'.ucfirst($client->departement).'</h5><hr class="sepdep" />'  : '';
					$dep = $client->departement;
			  }
		?>

	<p class="client">
			
			<?php if (isset($filleuls[$client->id])): ?>

				<a href="#" class="parrain" rel="popover" data-html="true" data-content="<?php foreach ($filleuls[$client->id] as $filleul) { echo $filleul->email.'<br/>'; } ?>" data-original-title="Filleul(s)"><span class="glyphicon glyphicon-user parrain" ><?php //echo $client->id ?></span></a>	

			<?php else: ?>

				<span class="glyphicon glyphicon-user clients"></span>

			<?php endif; ?>

			<?php if(\Auth::has_access('admin.list[delete]')): ?>
				&nbsp;<span class="glyphicon glyphicon-minus-sign deleteuser" data-email="<?php echo $client->email ?>" data-toggle="modal" data-url="<?php echo \Uri::base(false) ?>admin/list/delete/<?php echo $client->id ?>" data-target="#deleteuser" ></span>
			<?php endif; ?>
			
			<?php if(!empty($client->name)): ?>
				<span class="but"><?php echo ucfirst(strtolower($client->name)) ?> <?php echo ucfirst(strtolower($client->surname)) ?></span> -
			<?php endif; ?>

			<span class="mail"><a href="mailto:<?php echo $client->email ?>"><?php echo $client->email ?></a></span>
			
			<?php if(!empty($client->telephone)): ?>
				- <span class="tel">Tél. <?php echo preg_replace('/^.*(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/', '$1 $2 $3 $4 $5', $client->telephone) ?></span>
			<?php endif; ?>

			<?php if ($confirmed == "all"): ?>
				<span class="badge <?php echo $client->confirmed ?>" ><?php echo $status[$client->confirmed] ?></span>
			<?php endif; ?>
			
			<?php if (count($client['butdom_invoices']) > 0): ?>
				<a href="/admin/invoices/<?php echo $client->id ?>" type="button" class="btn btn-default btn-xs" >Factures</a>
			<?php endif; ?>
			
			<br />

	</p>

	<?php endforeach ?>

	

<?php else: ?>
	
	<hr />
	<?php if ($action == "operation" || $action == "operation_closed" ): ?>
		<p>Choisissez une opération dans le menu blanc ci-dessus.</p>
	<?php else: ?>
		<p>Aucun client.</p>
	<?php endif; ?>

<?php endif ?>


<?php echo isset($pagination) ? $pagination : '' ?>

<hr />

<p>
	<small>© CAFOM Distribution - 9-11 rue Jacquard 93310
	Le Pré Saint Gervais - Paris<br />
</p>


<!-- bootstrap modal for delete user -->
<div class="modal fade" id="deleteuser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Effacement</h4>
      </div>
      <div class="modal-body">
        <p class="delete-warning"><strong>Attention !</strong> Voulez-vous vraiment effacer le client <strong><span id="userdeleted"></span></strong> avec toutes ses factures ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
        <a href="#" id="deleteaction" type="button" class="btn btn-danger" >J'efface le client</a>

      </div>
    </div>
  </div>
</div>
<!-- end bootstrap delete user -->
