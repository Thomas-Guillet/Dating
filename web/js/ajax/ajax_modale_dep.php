<?php 

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'annonces.php';
include_once MODELS.'liste_dep.php';

//On récupère l'id compte
$iIdCompte = $_POST['compte'];
//On récupère l'id annonce
$iIdAnnonce = $_POST['annonce'];
//On récupère l'annonce
$aAnnonce = getAnnonceById($iIdAnnonce);
//On récupère le département
$iDep = substr($aAnnonce['cp_annonce'], 0, 2);
$aDep = getDepByNum($iDep);
$sDep = stripslashes($aDep['nom_min_dep']);

?>

<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3 class="modal-title">Attention</h3>
		</div>
		<div class="modal-body text-center grisFonce">
			<p class="lead">
				Ce job est situé dans le département <span class="bleuClair"><b><?= $iDep ?></b></span>,<br />
				<span class="bleuClair"><?= $sDep ?></span>.
			</p>
		</div>
		<div class="modal-footer">
			<p class="lead fontSize25">Es-tu sûr de vouloir postuler ?</p>
			<input type="hidden" id="iIdAnnonceDep" value="<?= $iIdAnnonce ?>" />
			<input type="hidden" id="iIdCompteDep" value="<?= $iIdCompte ?>" />
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
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('#btn-oui').click(function() {
			$.ajax({
				method: 'post',
				url: $('#ajax').val()+'ajax_dep.php',
				data: 'annonce='+$('#iIdAnnonceDep').val(),
				success: function() {
					$('#modaleDep').modal('hide');
          			postuler($('#iIdAnnonceDep').val(), $('#iIdCompteDep').val());
				}
			});
		});

	});
</script>