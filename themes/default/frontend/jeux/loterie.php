
<div class="loto">

		<h1 class="achat" >Voulez-vous être remboursé<br /> de votre achat ?</h1>

		<p class="lead">Indiquez-nous vite votre adresse e-mail,</p>
		<h3 class="cheque">pour gagner le montant de votre achat en chèque-cadeau. <sup>(1)</sup></h3>

</div>

<hr />
	
<div class="container formloto">
	<div class="row">
    	<div class="col-md-8 col-md-offset-2">

			<form id="formloto" class="form-group" data-validate="parsley" data-show-errors="true" action="/loterie" method="post" name="formloto" >
				
				<div class="form-group">
					<label for="email">Département de votre magasin BUT :</label>

					<div class="departement errorcontainer" ></div>
					<div class="btn-group" data-toggle="buttons" class="departement" >
					  <label class="departement btn btn-primary <?php echo ($error['departement']=="guadeloupe") ? 'active' : '' ?>">
					    	<input type="radio" name="departement" value="guadeloupe" data-error-container=".departement.errorcontainer" data-required="true" data-error-message="Vous devez sélectionner le département de Votre magasin" <?php echo ($error['departement']=="guadeloupe") ? 'CHECKED' : '' ?> > Guadeloupe
					  </label>

					  <label class="departement btn btn-primary <?php echo ($error['departement']=="martinique") ? 'active' : '' ?>">
					    	<input type="radio" name="departement" value="martinique" data-error-container=".departement.errorcontainer" data-required="true" data-error-message="Vous devez sélectionner le département de Votre magasin" <?php echo ($error['departement']=="martinique") ? 'CHECKED' : '' ?> > Martinique
					  </label>

					  <label class="departement btn btn-primary <?php echo ($error['departement']=="guyane") ? 'active' : '' ?>">
					    	<input type="radio" name="departement" value="guyane" data-error-container=".departement.errorcontainer" data-required="true" data-error-message="Vous devez sélectionner le département de Votre magasin" <?php echo ($error['departement']=="guyane") ? 'CHECKED' : '' ?> > Guyane
					  </label>

					  <label class="departement btn btn-primary <?php echo ($error['departement']=="reunion") ? 'active' : '' ?>">
					    	<input type="radio" name="departement" value="reunion" data-error-container=".departement.errorcontainer" data-required="true" data-error-message="Vous devez sélectionner le département de Votre magasin" <?php echo ($error['departement']=="reunion") ? 'CHECKED' : '' ?> > Réunion
					  </label>

					  <label class="departement btn btn-primary <?php echo ($error['departement']=="stmartin") ? 'active' : '' ?>">
					    	<input type="radio" name="departement" value="stmartin" data-error-container=".departement.errorcontainer" data-required="true" data-error-message="Vous devez sélectionner le département de Votre magasin" <?php echo ($error['departement']=="stmartin") ? 'CHECKED' : '' ?> > Saint Martin
					  </label>

					</div>
				</div>

				<label for="email">E-mail :</label>
					<div class="email errorcontainer" ></div>
				<div class="form-group">
					<input type="email" data-type="email" data-required="true" data-trigger="keyup focusin focusout change" data-error-container=".email.errorcontainer" data-validation-minlength="0" data-error-message="Entrez votre e-mail pour participer au jeu" value="<?php echo isset($error['email']) ? $error['email'] : '' ?>" name="email" placeholder="Entrez votre e-mail pour participer au jeu" class="form-control email" required >
				</div>

				<label for="invoice">Informations ticket d'achat :</label>
					<div class="invoice errorcontainer" ></div>
				<div class="form-group">
					<input type="text" value="<?php echo isset($error['invoice']) ? $error['invoice'] : '' ?>" name="invoice" data-trigger="keyup focusin focusout change" data-type="digits" data-minlength="6" data-required="true" data-error-container=".invoice.errorcontainer" data-validation-minlength="0" data-error-message="Entrez le numéro de votre ticket d'achat ( 6 chiffres sans espaces )" placeholder="Entrez le numéro de votre ticket d'achat ( 6 chiffres sans espaces )" class="form-control invoice" maxlength="6" required >
				</div>

				<div class="dateinvoice errorcontainer" ></div>
				<div class="form-group">
					<input type="text" class="form-control datepicker" value="<?php echo isset($error['dateinvoice']) ? \Date::forge($error['dateinvoice'])->format("%d/%m/%Y") : '' ?>" data-trigger="keyup focusin focusout change" data-regexp="^(3[01]|[12][0-9]|0[1-9])/(1[0-2]|0[1-9])/[0-9]{4}$" data-validation-minlength="0" data-error-message="Entrez la date de votre ticket d'achat ( JJ/MM/AAAA )" placeholder="Entrez la date de votre ticket d'achat ( JJ/MM/AAAA )" data-required="true" data-error-container=".dateinvoice.errorcontainer" name="dateinvoice" data-date-format="dd/mm/yy" >
				</div>
				
				<label for="name">Informations personnelles :</label>
					<div class="name errorcontainer" ></div>
				<div class="form-group">
					<input type="text" value="<?php echo isset($error['name']) ? $error['name'] : '' ?>" name="name" data-trigger="keyup focusin focusout change" data-required="true" data-minlength="2" data-maxlength="128" data-error-message="Entrez votre nom" data-error-container=".name.errorcontainer" data-validation-minlength="0" placeholder="Entrez votre nom" class="form-control name" required >
				</div>

				<div class="surname errorcontainer" ></div>
				<div class="form-group">
					<input type="text" value="<?php echo isset($error['surname']) ? $error['surname'] : '' ?>" name="surname" data-trigger="keyup focusin focusout change" data-required="true" data-minlength="2" data-maxlength="128" data-error-message="Entrez votre prénom" data-error-container=".surname.errorcontainer" data-validation-minlength="0" placeholder="Entrez votre prénom" class="form-control surname" required >
				</div>

				<div class="telephone errorcontainer" ></div>
				<div class="form-group">
					<input type="text" value="<?php echo isset($error['telephone']) ? $error['telephone'] : '' ?>" name="telephone" data-required="true" data-trigger="keyup focusin focusout change" data-type="digits" data-minlength="10" data-error-message="Entrez votre téléphone (10 chiffres sans espaces)" data-validation-minlength="0" data-error-container=".telephone.errorcontainer" placeholder="Entrez votre téléphone (10 chiffres sans espaces)" class="form-control telephone" maxlength="10" required >
				</div>
				
				<div class="checkbox">
				  <label>
				    <input type="checkbox" checked disabled="disabled" data-required="true" data-trigger="change" name="partenaires" > J'accepte de recevoir les actualités des magasins CAFOM
				  </label>
				</div>

				<div class="checkbox">
				  <label for="reglement" >J'ai pris connaissance du réglement et je l'accepte <sup>(1)</sup></label>
				    <input type="checkbox" data-required="true" data-trigger="change" data-error-message="Vous devez accepter les conditions du réglement" data-error-container=".reglement.errorcontainer" name="optin" >				  
				  	<div class="reglement errorcontainer" ></div>
				</div>
				
				<br/>

				<button type="submit" class="btn btn-success" >S'inscrire</button>

			</form>

		</div>
	</div>
</div>

<hr />

<div class="container reglement">
	<small>
		<strong><sup>(1)</sup> Extrait de règlement :</strong> La société CAFOM Distribution sis 9-11 rue Jacquard, 93310 Le Pré Saint Gervais - France, organise un Jeu avec obligation d’achat intitulé « Voulez-vous être remboursé de votre achat ? » du 28 janvier au 31 décembre 2013 inclus, durant les heures d’ouvertures de l’ensemble des Magasins Cafom dans les départements d’Outre-Mer.
		Dotation : Les gagnants du Jeu seront désignés par tirages au sort selon la périodicité indiquée en magasin et dans le règlement complet du Jeu. Les participants tirés au sort obtiendront le remboursement de leur achat effectué en magasin, par chèque cadeau, dans la limite d’un montant de 3000€ TTC. Le chèque cadeau est utilisable en une seule fois, hors promotion et articles soldés, pour tout achat d’un montant supérieur à sa valeur, et peut être complété par tout moyen de paiement. Il est valable pendant un an à compter de sa date d’émission, dans le Magasin où le participant a joué et ne peut faire l’objet d’aucune contrepartie monétaire. Le chèque-cadeau est  nominatif et ne peut  pas être transmis à des tiers.
		Le règlement du Jeu, déposé chez Maître Manceau, Huissier de justice à Paris, sera adressé gratuitement sur demande écrite à l’adresse de la société CAFOM Distribution indiquée ci-dessus (timbre remboursé au tarif lent en vigueur sur demande conjointe).
		Conformément à la loi °78-17 informatique et libertés du 6 janvier 1978, modifiée par la loi du 6 aout 2004, les participants disposent d’un droit d’accès, de rectification, d’opposition et de suppression des données personnelles les concernant en écrivant à l’adresse de la société CAFOM Distribution indiquée ci-dessus.
		Les gagnants autorisent gracieusement la société CAFOM  Distribution à utiliser leur nom et leur prénom, pour les besoins de la communication faite autour du Jeu uniquement.
		Cafom Distribution, SAS au capital de 577 600€, Siège social : 9/11 rue jacquard, 93310 Le Pré Saint Gervais - RCS Bobigny 337 810 501.
	</small>
</div>

<br />
