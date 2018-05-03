<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'comptes_referal.php';

//On récupère l'id compte
$iIdCompte = $_SESSION[SESSION]['compte'];

//On récupère le classement
$aAllClassement = getClassement();
//On récupère la taille du tableau
$iClassement = count($aAllClassement);
$aClassement = array();
$bCompte = false;
$iPlace = 1;
$i = 0;
while (($i < $iClassement) && (count($aClassement) < ATOUT_NB_CLASSEMENT)) {
	if ($i < ATOUT_NB_PODIUM) {
		//On récupère le nom
		$sNom = stripslashes($aAllClassement[$i]['prenom']).' '.substr(stripslashes($aAllClassement[$i]['nom']), 0, 1).'.';
		//On récupère les points
		$iPoints = $aAllClassement[$i]['points'];
		//On récupère la couleur
		$sColor = '';
		if ($aAllClassement[$i]['id_compte'] == $iIdCompte) {
			$sColor = 'bleuClair';
			$bCompte = true;
		}
		$aClassement[] = array(
			'place' => $iPlace,
			'nom' => $sNom,
			'color' => $sColor,
			'points' => $iPoints
		);
		$iPlace++;
		$i++;
	} else if ($bCompte) {
		//On récupère le nom
		$sNom = stripslashes($aAllClassement[$i]['prenom']).' '.substr(stripslashes($aAllClassement[$i]['nom']), 0, 1).'.';
		//On récupère les points
		$iPoints = $aAllClassement[$i]['points'];
		$aClassement[] = array(
			'place' => $iPlace,
			'nom' => $sNom,
			'color' => '',
			'points' => $iPoints
		);
		$iPlace++;
		$i++;
	} else if ($aAllClassement[$i]['id_compte'] == $iIdCompte) {
		if ($iPlace > 4) {
			//On récupère le nom
			$sNom = stripslashes($aAllClassement[$i-1]['prenom']).' '.substr(stripslashes($aAllClassement[$i-1]['nom']), 0, 1).'.';
			//On récupère les points
			$iPoints = $aAllClassement[$i-1]['points'];
			$aClassement[] = array(
				'place' => $iPlace-1,
				'nom' => $sNom,
				'color' => '',
				'points' => $iPoints
			);
		}
		//On récupère le nom
		$sNom = stripslashes($aAllClassement[$i]['prenom']).' '.substr(stripslashes($aAllClassement[$i]['nom']), 0, 1).'.';
		//On récupère les points
		$iPoints = $aAllClassement[$i]['points'];
		$aClassement[] = array(
			'place' => $iPlace,
			'nom' => $sNom,
			'color' => 'bleuClair',
			'points' => $iPoints
		);
		$bCompte = true;
		$iPlace++;
		$i++;
	} else {
		$iPlace++;
		$i++;
	}
}

if (count($aClassement) < ATOUT_NB_CLASSEMENT) {
	$i = count($aClassement);
	while (($i < $iClassement) && ($i < ATOUT_NB_CLASSEMENT)) {
		//On récupère le nom
		$sNom = stripslashes($aAllClassement[$i]['prenom']).' '.substr(stripslashes($aAllClassement[$i]['nom']), 0, 1).'.';
		//On récupère les points
		$iPoints = $aAllClassement[$i]['points'];
		$aClassement[] = array(
			'place' => $i+1,
			'nom' => $sNom,
			'color' => '',
			'points' => $iPoints
		);
		$i++;
	}
	for ($i=$iClassement; $i<ATOUT_NB_CLASSEMENT; $i++) {
		$aClassement[] = array(
			'place' => $iPlace,
			'nom' => 'Place à prendre',
			'color' => '',
			'points' => NULL
		);
		$iPlace++;
	}
}

?>

<?php foreach ($aClassement as $aDemandeur) : ?>
	<div class="paddingLeft12 paddingRight12 borderElementGamification">
		<div class="row vertical-align">
			<div class="col-xs-3 col-md-3">
				<?php if ($aDemandeur['place'] == 1) : ?>
					<div class="star star_text_or marginTop5">
						<span class="fa-stack fa-2x">
						<i class="fa fa-star-o fa-stack-2x"></i>
						<strong class="fa-stack-1x star-text fontSize16">1</strong>
						</span>
					</div>
				<?php elseif ($aDemandeur['place'] == 2) : ?>
					<div class="star star_text_argent marginTop5">
						<span class="fa-stack fa-2x">
						<i class="fa fa-star-o fa-stack-2x"></i>
						<strong class="fa-stack-1x star-text fontSize16">2</strong>
						</span>
					</div>
				<?php elseif ($aDemandeur['place'] == 3) : ?>
					<div class="star star_text_bronze marginTop5">
						<span class="fa-stack fa-2x">
						<i class="fa fa-star-o fa-stack-2x"></i>
						<strong class="fa-stack-1x star-text fontSize16">3</strong>
						</span>
					</div>
				<?php else : ?>
					<div class="<?= $aDemandeur['color'] ?>">
						<span class="fa-stack fa-2x">
							<i class="fa fa-stack-2x"></i>
							<strong class="fa-stack-1x star-text fontSize16"><?= $aDemandeur['place'] ?></strong>
						</span>
					</div>
				<?php endif; ?>
			</div>    
			<div class="col-xs-5 col-md-6">
				<div class="identite_content fontSize16 <?= $aDemandeur['color'] ?>"><?= $aDemandeur['nom'] ?></div>
			</div>
			<div class="col-xs-3 col-md-3">
				<?php if ($aDemandeur['points']) : ?>
					<span class="fontSize16"><b><?= $aDemandeur['points'] ?> pts</b></span>
				<?php endif; ?>
			</div> 
		</div>
	</div>
	<?php if ($aDemandeur['place'] == 3) : ?>
		<div class="row">
			<div class="col-xs-offset-2 col-xs-8">
				<hr />
			</div>
		</div>
	<?php endif; ?>
<?php endforeach; ?>