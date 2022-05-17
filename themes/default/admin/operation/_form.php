<?php echo Form::open(array("class"=>"form-horizontal")); ?>
		
		<div class="form-group">
			<?php echo Form::label('Titre', 'title', array('class'=>'control-label')); ?>

				<?php echo Form::input('title', Input::post('title', isset($butdom_operation) ? $butdom_operation->title : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Titre de l\'opé')); ?>

		</div>

		<div class="form-group">
			<?php echo Form::label('Sous-titre de l\'évènement', 'event', array('class'=>'control-label')); ?>

				<?php echo Form::input('event', Input::post('event', isset($butdom_operation) ? $butdom_operation->event : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Sous-titre de l\'évènement')); ?>

		</div>

		<div class="form-group">
			<?php echo Form::label('Département', 'departement', array('class'=>'control-label')); ?>
				
				<?php echo Form::select('departement', isset($butdom_operation) ? $butdom_operation->departement : NULL, $departements, array('class' => 'col-md-4 form-control', 'placeholder'=>'Département')); ?>

		</div>

		<div class="form-group">
			<?php echo Form::label('Uri', 'name', array('class'=>'control-label')); ?>

				<?php echo Form::input('name', Input::post('name', isset($butdom_operation) ? $butdom_operation->name : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Uri  de l\'opé')); ?>

		</div>

		

		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Sauvegarde', array('class' => 'btn btn-primary')); ?>
		</div>

<?php echo Form::close(); ?>