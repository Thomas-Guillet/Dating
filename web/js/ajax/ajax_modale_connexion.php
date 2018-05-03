<?php

include_once '../../../config/constantes.php';

?>

<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3 class="modal-title">Connexion</h3>
		</div>
		<div class="modal-body">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="mMail" class="col-sm-3 control-label">Email</label>
					<div class="col-sm-8">
						<input type="email" class="form-control input-bleu" id="mMail" />
					</div>
				</div>
				<div class="form-group">
					<label for="mPassword" class="col-sm-3 control-label">Mot de passe</label>
					<div class="col-sm-8">
						<input type="password" class="form-control input-bleu" id="mPassword" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-8">
						<button type="button" class="btn btn-link" id="btnPassword">J'ai oublié mon mot de passe</button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-8">
						<p id="alerteConnect" class="alerte"></p>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" id="idAnnonce" value="<?= $_POST['annonce'] ?>" />
			<div class="col-sm-6">
				<button type="button" class="btn btn-lg btn-primary col-xs-12" id="btnInscription">
		        	<span class="col-xs-10 btn-text-left">S'inscrire</span>
		        	<span class="glyphicon glyphicon-play col-xs-2" aria-hidden="true"></span>
		        </button>
			</div>
			<div class="visible-xs"><br /><br /><br /></div>
			<div class="col-sm-6">
				<button type="button" class="btn btn-lg btn-primary col-xs-12" id="btnConnect">
					<span class="col-xs-10 btn-text-left">Se connecter</span>
					<span class="glyphicon glyphicon-play col-xs-2" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		//Connexion
		$('#btnConnect').click(function() {
			//On vérifie le mail
			if ($('#mMail').val()) {
				//On vérifie le mot de passe
				if ($('#mPassword').val()) {
					$.ajax({
						method: 'post',
						url: $('#ajax').val()+'ajax_connexion.php',
						data: 'mail='+$('#mMail').val()+'&password='+$('#mPassword').val(),
						dataType: 'json',
						success: function(data) {
							if (data.etat == 'success') {
								$('#modale').modal('hide');
								$('#reload').val('oui');
								//On récupère la session
								$.ajax({
									method: 'post',
									url: $('#ajax').val()+'ajax_recup_session.php',
									success: function(data) {
										var session = JSON.parse(data);
										postuler($('#idAnnonce').val(), session.compte);
									}
								});
							} else if (data.etat == 'desactiver') {
								$.ajax({
									method: 'post',
									url: $('#ajax').val()+'ajax_modale_compte_desactive.php',
									data: 'compte='+data.compte+'&annonce='+$('#idAnnonce').val(),
									success: function(data) {
										$('#modale').modal('hide');
										$('#modaleDesactivation').html(data).modal('show');
									}
								});
							} else {
								$('#alerteConnect').fadeOut('slow', function() {
									$('#alerteConnect').html(data.texte).fadeIn('slow');
								});
							}
						}
					});
				} else {
					$('#alerteConnect').fadeOut('slow', function() {
						$('#alerteConnect').html('Il manque un mot de passe').fadeIn('slow');
					});
				}
			} else {
				$('#alerteConnect').fadeOut('slow', function() {
					$('#alerteConnect').html('Il manque un email').fadeIn('slow');
				});
			}
		});

		//Mot de passe oublié
		$('#btnPassword').click(function() {
			//On vérifie le mail
			if ($('#mMail').val()) {
				$.ajax({
					method: 'post',
					url: $('#ajax').val()+'ajax_forget_password.php',
					data: 'mail='+$('#mMail').val(),
					success: function(data) {
						$('#alerteConnect').fadeOut('slow', function() {
							$('#alerteConnect').html(data).fadeIn('slow');
						});
					}
				});
			} else {
				$('#alerteConnect').fadeOut('slow', function() {
					$('#alerteConnect').html('Il manque un email').fadeIn('slow');
				});
			}
		});

		//Inscription
		$('#btnInscription').click(function() {
			$(location).attr('href', $('#url').val()+'je-cherche-un-job/inscription/'+$('#code').val()+'/');
		});

	});
</script>