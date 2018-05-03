<?php 

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'annonces_types.php';
include_once MODELS.'annonces_durees.php';

$iIdCompte = $_SESSION[SESSION]['compte'];
$aJours = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');

$sIdsContrats = $_POST['contrats'];
$aIdsContrats = str_split($sIdsContrats);
$sContrats = '';
foreach ($aIdsContrats as $iIdContrat) {
	$aContrat = getTypeAnnonceById($iIdContrat);
	$sContrats .= $aContrat['nom_type_annonce'].' - ';
}
$sContrats = substr($sContrats, 0, strlen($sContrats) - 3);
$iNbContrats = count($aIdsContrats);

$sIdsDurees = $_POST['durees'];
$aIdsDurees = str_split($sIdsDurees);
$sDurees = '';
foreach ($aIdsDurees as $iIdDuree) {
	$aDuree = getDureeById($iIdDuree);
	$sDurees .= $aDuree['nom_duree_annonce'].' - ';
}
$sDurees = substr($sDurees, 0, strlen($sDurees) - 3);
$iNbDurees = count($aIdsDurees);

$sDisposBinaire = $_POST['dispos'];
$aDispos = str_split($sDisposBinaire, 24);
$sDispos = '';
foreach ($aDispos as $iKey => $sHoraires) {
	$aHoraires = str_split($sHoraires);
	$sDispo = '';
	$bOut = true;
	$iHeure = 0;
	foreach ($aHoraires as $bHoraire) {
		if ($bHoraire && $bOut) {
			$sDispo .= 'de '.$iHeure.'H';
			$bOut = false;
		} else if (!$bHoraire && !$bOut){
			$sDispo .= ' à '.$iHeure.'H, ';
			$bOut = true;
		} else if ($bHoraire && !$bOut && ($iHeure == 23)) {
			$sDispo .= ' à 24H, ';
		}
		$iHeure++;
	}
	if ($sDispo) {
		$sDispo = substr($sDispo, 0, strlen($sDispo)-2);
		$sDispos .= '<span class="bleuClair"><b>Le '.$aJours[$iKey].'</b></span> : ';
		$sDispos .= $sDispo.'<br />';
	}
}

?>

<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3 class="modal-title text-center bleuClair">Tes disponibilités (étape 3/3)</h3>
		</div>
		<div class="modal-body grisFonce text-center">
			<p>
				<span class="lead">
					<?php if ($iNbContrats < 2) : ?>
						Tu es prêt(e) à accepter le contrat :
					<?php else : ?>
						Tu es prêt(e) à accepter les contrats :
					<?php endif; ?>
				</span><br />
				<span class="bleuClair"><b><?= $sContrats ?></b></span>
			</p>
			<p>
				<span class="lead">
					<?php if ($iNbDurees < 2) : ?>
						Le temps de travail qui te convient est :
					<?php else : ?>
						Les temps de travail qui te conviennent sont :
					<?php endif; ?>
				</span><br />
				<span class="bleuClair"><b><?= $sDurees ?></b></span>
			</p>
			<p>
				<span class="lead">Tu es disponible :</span><br />
				<?= $sDispos ?>
			</p>
		</div>
		<div class="modal-footer">
			<form action="#" method="post">
				<input type="hidden" name="contrats" value="<?= $sIdsContrats ?>" />
				<input type="hidden" name="durees" value="<?= $sIdsDurees ?>" />
				<input type="hidden" name="horaires" value="<?= $sDisposBinaire ?>" />
				<input type="hidden" name="id_compte" value="<?= $iIdCompte ?>" />
				<div class="col-sm-offset-3 col-sm-6">
					<button type="submit" name="modif_dispos" class="btn btn-primary btn-shadow col-xs-offset-1 col-xs-10 marginTop20">
						<span class="col-xs-10 btn-text-left">Valider</span>
						<span class="glyphicon glyphicon-ok col-xs-2" aria-hidden="true"></span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>