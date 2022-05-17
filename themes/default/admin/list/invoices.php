<?php $urlback = !\Input::referrer() ? \Uri::base(false).'admin/clients' : \Input::referrer(); ?>

<p><small><?php echo \Html::anchor( $urlback, '[ Retour à la liste ]');?></small></p>

	<h2>Facture(s) client</h2>

	<?php  if ($startdate != '' || $enddate != ''): ?>

		<h4 style="color:#888">Facture(s) du <?php echo $str_startdate ?> au <?php echo $str_enddate ?></h4>

	<?php endif; ?>

	<h3 class="departement">
		<span class="glyphicon glyphicon-user"></span>
		<?php echo ucfirst(strtolower($client->name)) . ' ' . ucfirst(strtolower($client->surname)) . ' <small class="btn btn-default btn-xs invoice '.$client->confirmed.'">' . $status[$client->confirmed] . '</small>' ?>
		<small class="btn btn-primary btn-xs"><?php echo ucfirst($client->departement) ?></small>
	</h3>
	<span><strong><?php echo $client->email ?></strong> - </span>
	<span>Tél. <?php echo preg_replace('/^.*(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/', '$1 $2 $3 $4 $5', $client->telephone) ?></span>

	<hr />

		<?php if ($invoices): ?>

			<?php foreach ($invoices as $invoice): ?>

					<?php if ($invoice->date >= $startdate && $invoice->date <= $enddate): ?>

						<p><span class="glyphicon glyphicon-list-alt invoice"></span> Facture <span class="btn-success btn-xs invoice"><?php echo $invoice->number ?></span> du <strong><?php echo \Date::forge($invoice->date)->format("%d/%m/%Y") ?></strong>

					<?php else: ?>

						<p><span class="glyphicon glyphicon-list-alt invoice none"></span> Facture <span class="btn-default btn-xs invoice"><?php echo $invoice->number ?></span> du <strong><?php echo \Date::forge($invoice->date)->format("%d/%m/%Y") ?></strong>

					<?php endif; ?>

			<?php endforeach ?>

		<?php else: ?>


			<p>Pas de factures créées pour ce client</p>


		<?php endif ?>
