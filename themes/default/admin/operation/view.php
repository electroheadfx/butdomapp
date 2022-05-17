<h2>Jeu “<?php echo $butdom_operation->title; ?>” <span class='muted'><small>#<?php echo $butdom_operation->id; ?></small></span></h2>

<div class="btn-toolbar">
	<div class="btn-group">
		<?php echo Html::anchor($jeu, '<i class="glyphicon glyphicon-eye-open"></i> Visiter la page du jeu', array('class' => 'btn btn-success', 'target' => '_blank')); ?>
	</div>
</div>

<br/>

<div class="campagne" >

	<h4>Stats par département</h4>
	<hr/>
		
	<!-- Nav tabs -->
	<ul id="myTab" class="nav nav-tabs">
		
		<?php foreach ($departements as $key => $departement): ?>

			<li class="<?php echo $key == 'guadeloupe' ? 'active' : '' ?>" ><a href="#<?php echo $departement['title'] ?>" data-toggle="tab"><?php echo $departement['title'] ?></a></li>

		<?php endforeach; ?>

	</ul>
	
	<!-- Tab panes -->
	<div id="myTabContent" class="tab-content">
		
		<?php $i=0; ?>
		<?php foreach ($departements as $key => $departement): ?>

			<!-- panel -->
			<div class="tab-pane fade in <?php echo $key == 'guadeloupe' ? 'active' : '' ?>" id="<?php echo $departement['title'] ?>">
					
					<!-- Accordéons -->
					<div class="panel-group <?php echo $i; ?>" id="accordion<?php echo $i; ?>">
						
						<div class="highlight">
						<br/>

							<?php if (isset($departement['stats'])): ?>
								
								<div class="row">

									<?php foreach ($departement['stats'] as $key => $stat): ?>
										
										<div class="col-lg-6 stat">
										  <div class="panel panel-<?php echo $statinfo[$key]['style'];  ?>">
										    <div class="panel-heading">
										      <div class="row">
										        <div class="col-xs-6">
										          <i class="fa fa-<?php echo $statinfo[$key]['icon'];  ?> fa-5x"></i>
										        </div>
										        <div class="col-xs-12 text-right">
										          <p class="announcement-heading"><?php echo $stat ?></p>
										          <p class="announcement-text"><?php echo $statinfo[$key]['title'] ?></p>
										        </div>
										      </div>
										    </div>
										    <a href="#">
										      <div class="panel-footer announcement-bottom">
										        <div class="row">
										          <div class="col-xs-12 desc">
										            <i><?php echo $statinfo[$key]['desc']; ?></i>
										          </div>
										        </div>
										      </div>
										    </a>
										  </div>
										</div>

									<?php endforeach; ?>
								
								</div>

							<?php else: ?>

								<p>Pas de données</p>

							<?php endif; ?>
						<br/>
						</div>

					</div>

			</div>

		<?php endforeach; ?>

	</div>

</div>

<br/>

<div class="group-separator">
	<div class="btn-group">
	  <a type="button" class="btn btn-default" href="/admin/operation"><span class="glyphicon glyphicon-share-alt"></span> Retour</a>
	</div>
</div>