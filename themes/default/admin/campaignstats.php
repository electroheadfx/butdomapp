
<div class="api">

	<h1>Dashboard</h1>

	<hr/>
	
	<h4>Stats mailchimp</h4>

	<hr/>

	<div class="departements">

		<?php foreach ($departements->groups as $departement): ?>

			<button class="btn btn-primary departement stat" data-toggle="tooltip" data-placement="bottom" title="Total d'inscriptions dans la base de données Mailchimp concernant uniquement le département <?php echo $departement->name ?>" >
				<span class="glyphicon glyphicon-user"></span>  
				<?php echo $departement->name ?>
				<span class="badge badge-inverse"><?php echo number_format($departement->subscribers, 0, ".", "  ") ?></span>
			</button>

		<?php endforeach; ?>
		
	</div>

	<hr/>

	<?php foreach ($api as $key): ?>
			
			<button class="btn btn-<?php echo $$key->class ?> btn-default stat" data-toggle="tooltip" data-placement="bottom" title="<?php echo $$key->desc ?>" >
				<?php if (isset($$key->icon)): ?>
					<span class="glyphicon glyphicon-<?php echo $$key->icon ?>"></span>  
				<?php endif; ?>
				<?php echo $$key->title ?>
				<span class="<?php echo ($$key->class == 'btn-default') ? 'label' : 'badge'; ?> label-<?php echo isset($$key->badge) ? $$key->badge : ''; ?>"><?php echo number_format($$key->data, 0, ".", "  ") ?></span>
			</button>

		<?php echo isset($$key->html) ? $$key->html : ''; ?>

	<?php endforeach; ?>

	<hr/>

</div>


<div class="campagne" >

	<h4>Stats dernières campagnes</h4>
	<hr/>
		
	<!-- Nav tabs -->
	<ul id="myTab" class="nav nav-tabs">
		
		<?php foreach ($campaignsfolders as $campaignfolder): ?>

			<li class="<?php echo $campaignfolder->key == 'guadeloupe' ? 'active' : '' ?>" ><a href="#<?php echo $campaignfolder->key ?>" data-toggle="tab"><?php echo $campaignfolder->title ?></a></li>

		<?php endforeach; ?>

	</ul>
	
	<!-- Tab panes -->
	<div id="myTabContent" class="tab-content">
		
		<?php $i=0; ?>
		<?php foreach ($campaignsfolders as $campaignfolder): ?>

			<!-- panel -->
			<div class="tab-pane fade in <?php echo $campaignfolder->key == 'guadeloupe' ? 'active' : '' ?>" id="<?php echo $campaignfolder->key ?>">
					
					<!-- Accordéons -->
					<div class="panel-group <?php echo $i; ?>" id="accordion<?php echo $i; ?>">
						
						<?php foreach ($campaignfolder->campaigns as $campaign): ?>

							<!-- Accordéon 1 -->
							<div class="panel">
								<div class="panel-heading">
									<h5 class="panel-title">
										<a data-toggle="collapse" class="collapsed" data-parent="#accordion<?php echo $i; ?>" href="#<?php echo $campaign->id ?>">
											<span class="glyphicon glyphicon-eye-open"></span> <strong>"<?php echo $campaign->subject ?>"</strong> <small>envoyé le <i><?php echo date("d/m/Y à G:i:s", strtotime($campaign->send_time)) ?></i></small>
										</a>
									</h5>
								</div>
								<div id="<?php echo $campaign->id ?>" class="panel-collapse collapse">
									<div class="panel-body">
										
										<div class="archivestats">

											<h5>Stats :</h5>
										
										</div>

										<div class="archiveweb">

											<h5>Vue :</h5>
											<div class="html">
												<?php echo $campaign->archiveweb ?>
											</div>

										</div>

									</div>
								</div>
							</div>
						
						<?php endforeach; ?>						
						<?php $i++; ?>

					</div>

			</div>

		<?php endforeach; ?>


	</div>

</div>

<hr/>

<div class="dashboard" >

	<h4>Listes inscriptions</h4>

	<hr/>

	<a href="<?php echo \Uri::base(false) ?>/admin/clients/guadeloupe/simple">
		<?php echo \Theme::instance()->asset->img('dashboard/simple.png', array('alt' => 'list tous')) ?>
	</a>

	<a href="<?php echo \Uri::base(false) ?>/admin/clients/all/loto">
		<?php echo \Theme::instance()->asset->img('dashboard/loto.png', array('alt' => 'list loto')) ?>
	</a>

	<a href="<?php echo \Uri::base(false) ?>/admin/clients/guadeloupe/operation">
		<?php echo \Theme::instance()->asset->img('dashboard/operation.png', array('alt' => 'liste operation')) ?>
	</a>

</div>