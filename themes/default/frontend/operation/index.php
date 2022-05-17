<div class="row">

	<div class="col-md-4">
		<?php if (! empty($source)) {
					echo \Html::img($source, array('class' => "cartecadeau"));
			  }
		?>
		
	</div>

	<div class="col-md-8">
		
		<h2 class="but" ><?php echo $action ?></h2>

		<?php echo $form; ?>
		
	</div>

</div>




