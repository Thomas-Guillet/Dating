<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'annonces.php';
include_once MODELS.'liste_annonce_avantages_inconvenients.php';

//On récupère l'id compte
$iIdCompte = $_POST['compte'];
//On récupère l'id annonce
$iIdAnnonce = $_POST['annonce'];
//On récupère l'annonce
$aAnnonce = getAnnonceById($iIdAnnonce);
//On récupère les ids inconvénients
$aIdsInconvenients = unserialize($aAnnonce['ids_inconvenients']);
$aInconvenients = array();
foreach ($aIdsInconvenients as $iIdInc) {
	//On récupère l'inconvénient
	$aInconvenient = getAvIncById($iIdInc);
	$sInconvenient = stripslashes($aInconvenient['nom_av_inc']);
	$aInconvenients[] = $sInconvenient;
}

?>

<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3 class="modal-title">Attention</h3>
		</div>
		<div class="modal-body text-center grisFonce">
			<p class="lead">
				Ce job possède les spécificités suivantes :
			</p>
			<div class="row">
				<ul class="text-left lead bleuClair col-xs-offset-2 col-xs-8">
					<?php foreach ($aInconvenients as $sInconvenient) : ?>
						<li><?= $sInconvenient ?></li>
					<?php endforeach; ?>
				</ul>
			</div><br />
			<p class="lead fontSize25">Es-tu sûr(e) de vouloir postuler ?</p>
			<div class="row">
				<div class="col-sm-offset-1 col-sm-5">
					<button type="button" class="btn btn-lg btn-primary col-xs-12" id="btn-oui">
			        	<span class="col-xs-10 btn-text-left">OUI</span>
			        	<span class="glyphicon glyphicon-play col-xs-2" aria-hidden="true"></span>
			        </button>
				</div>
				<div class="visible-xs"><br /><br /><br /></div>
				<div class="col-sm-5">
					<button type="button" class="btn btn-lg btn-default col-xs-12" data-dismiss="modal" id="btn-non">
						<span class="col-xs-10 btn-text-left">NON</span>
						<span class="glyphicon glyphicon-play col-xs-2" aria-hidden="true"></span>
					</button>
				</div>
			</div><br /><br />
			<input type="hidden" id="iIdAnnonceInc" value="<?= $iIdAnnonce ?>" />
			<input type="hidden" id="iIdCompteInc" value="<?= $iIdCompte ?>" />
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('#btn-oui').click(function() {
			$.ajax({
				method: 'post',
				url: $('#ajax').val()+'ajax_inconvenients.php',
				data: 'annonce='+$('#iIdAnnonceInc').val(),
				success: function() {
					$('#modaleInconvenients').modal('hide');
					postuler($('#iIdAnnonceInc').val(), $('#iIdCompteInc').val());
				}
			});
		});

	});
</script>