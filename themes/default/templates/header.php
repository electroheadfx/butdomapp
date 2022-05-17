
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="fr">

		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="but dom">
			<meta name="author" content="b2see">
			<title><?php echo $title; ?></title>

			<?php
				echo \Theme::instance()->asset->css(array('bootstrap.css','app.css'));
				echo \Theme::instance()->asset->render('header');
			?>
			<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]>
				<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->

		</head>

	<body <?php echo \Auth::check() ? 'class="admin"' : ''; ?> <?php if (!empty($ope)){echo "style=\"background: url('".$ope."');\"";} ?>>

		<div class="navbar-fixed-top">
			
			<div class="navbar navbar-inverse" style="background-color:#3a3a3a;">

				<div class="container">

					<div class="header">
						
						<div class="bg"><?php echo \Theme::instance()->asset->img("but-head.jpg", array('alt' => 'header but', 'height' => '99' )) ?></div>

						<div class="logo"><?php echo \Theme::instance()->asset->img("but.jpg", array('alt' => 'logo but', 'height' => '79' )) ?></div>

					</div>

					
				</div>
				
			</div>

		</div>

		<?php if ( \Auth::check() ): ?>

			<div class="navbar-fixed-top navbar-inverse menu" >
				  <div class="container">
					<div class="navbar-header">
					  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
					  <a class="navbar-brand" href="#"><?php echo \Auth::get_screen_name(); ?></a>
					</div>
					<div class="navbar-collapse collapse">

						<ul class="nav navbar-nav">

							<li <?php echo \Uri::segment(2) == 'dashboard' ? 'class="active"' : '' ?> >		<a href="/admin/dashboard">Dashboard</a></li>
							
								<li class="dropdown <?php echo in_array(\Uri::segment(2), array('import', 'operation')) ? 'active' : '' ?>">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Outils admin <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<?php if(\Auth::has_access('admin.list[modify]')): ?>
											<li <?php echo \Uri::segment(2) == 'import' ? 'class="active"' : '' ?> >	<a href="/admin/import">Import</a></li>
										<?php endif; ?>
										<li <?php echo \Uri::segment(2) == 'operation' ? 'class="active"' : '' ?> >	<a href="/admin/operation">Jeux opérations</a></li>
									</ul>
								</li>

							<li class="dropdown <?php echo \Uri::segment(1) == 'loterie' ? 'active' : '' ?>">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Frontend <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li <?php echo \Uri::segment(1) == 'loterie' ? 'class="active"' : '' ?> ><a href="/loterie">Page inscriptions en magasin</a></li>
								</ul>
							</li>

							<li class="dropdown <?php echo in_array($action, array('simple', 'loto', 'operation')) ? 'active' : '' ?>">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Listes <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li <?php echo $action == 'simple' ? 'class="active"' : '' ?> >			<a href="/admin/clients/guadeloupe/simple">Toutes les inscriptions</a></li>
									<li <?php echo $action == 'loto' ? 'class="active"' : '' ?> >			<a href="/admin/clients/all/loto">Inscriptions en magasins (loterie)</a></li>
									<li <?php echo $action == 'operation' ? 'class="active"' : '' ?> >		<a href="/admin/clients/guadeloupe/operation">Inscriptions Jeux</a></li>
								</ul>
							</li>

							<!-- <li <?php //echo \Uri::segment(2) == 'support' ? 'class="active"' : '' ?> >		<a href="/admin/support">Support</a></li> -->

							<?php if ( \Auth::check() ): ?>
								<li <?php echo \Uri::segment(2) == 'logout' ? 'class="active"' : '' ?> >	<a href="/users/logout" >Déconnection</a></li>
							<?php else: ?>
								<li <?php echo \Uri::segment(2) == 'login' ? 'class="active"' : '' ?> >		<a href="/users/login">Connection</a></li>
							<?php endif; ?>

						</ul>

					</div><!--/.navbar-collapse -->

				  </div>
			</div>
		<?php endif; ?>

		<!-- Begin messages -->
			
			<div class="message"><?php echo $messages; ?></div>
		
		<!-- End of messages -->

		<div class="container but">