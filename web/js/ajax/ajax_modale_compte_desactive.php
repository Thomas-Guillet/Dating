<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'logs_comptes_etats.php';
include_once MODELS.'comptes_raisons_desactivation.php';

//On récupère l'id compte
$iIdCompte = $_POST['compte'];
//On récupère l'annonce
$iIdAnnonce = NULL;
if (isset($_POST['annonce'])) {
	$iIdAnnonce = $_POST['annonce'];
}
//On récupère le log désactivation
$aLog = getLogDesactivByIdCompte($iIdCompte);
//On récupère la date
$dDateDesactivation = date('d-m-Y', strtotime($aLog['date_etat_compte']));
$dHourDesactivation = date('H:i', strtotime($aLog['date_etat_compte']));
//On récupère la raison
$sRaison = NULL;
if ($aLog['id_raison']) {
	$aRaison = getRaisonDesactivationById($aLog['id_raison']);
	$sRaison = stripslashes($aRaison['raison']);
	//On récupère le commentaire
	$sCommentaire = NULL;
	if ($aLog['id_raison'] == 3) {
		$sCommentaire = $aLog['commentaire'];
	}
}

?>

<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3 class="modal-title">Connexion</h4>
		</div>
		<div class="modal-body grisFonce">
			<p class="text-center">
				Ton compte a été désactivé le <?= $dDateDesactivation ?> à <?= $dHourDesactivation ?>
				<?php if ($sRaison) : ?>
					<br />
					pour la raison suivante : <?= $sRaison ?>
					<?php if ($sCommentaire) : ?>
						<br />
						"<?= $sCommentaire ?>"
					<?php endif; ?>
				<?php endif; ?>
			</p>
			<br />
			<p class="lead text-center">Tu veux réactiver ton compte ?</p>
		</div>
		<div class="modal-footer">
			<input type="hidden" id="compte" value="<?= $iIdCompte ?>" />
			<input type="hidden" id="idAnnoncePostule" value="<?= $iIdAnnonce ?>" />
			<button type="button" id="btn-activer" class="btn btn-theme">Je réactive mon compte </button>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('#btn-activer').click(function() {
			$.ajax({
				method: 'post',
				url: $('#ajax').val()+'ajax_activer_compte.php',
				data: 'compte='+$('#compte').val(),
				success: function(data) {
					if ($('#idAnnoncePostule').val()) {
						$('#modale').modal('hide');
						$('#reload').val('oui');
						//On récupère la session
						$.ajax({
							method: 'post',
							url: $('#ajax').val()+'ajax_recup_session.php',
							success: function(data) {
								var session = JSON.parse(data);
								postuler($('#idAnnoncePostule').val(), session.compte);
							}
						});
					} else {
						$(location).attr('href', $('#url').val()+data);
					}
				}
			});
		});

	});
</script>