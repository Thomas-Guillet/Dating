<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'liste_cp_ville.php';
include_once MODELS.'annonces.php';
include_once MODELS.'annonces_postulants.php';
include_once MODELS.'annonces_types.php';
include_once MODELS.'compatibilites.php';
include_once MODELS.'comptes_criteres.php';
include_once MODELS.'comptes.php';
include_once MODELS.'liste_dep.php';
include_once MODELS.'liste_code_rome.php';
include_once CONTROLLERS.'algo.php';
include_once CONTROLLERS.'fonctions.php';

$bDisplayResults = false;
$iFirstIdAnnonce = NULL;
$iLastIdAnnonce = NULL;
$sTypeSearch = NULL;
$sDisplayPrec = 'non';
$sDisplaySuiv = 'non';

//On enregistre en session
if (!isset($_SESSION[SESSION])) {
	$_SESSION[SESSION] = array();
}
$_SESSION[SESSION]['recherche'] = array(
	'search' => $_POST['search'],
	'option' => $_POST['option'],
	'id_annonce' => $_POST['id_annonce']
);

$_POST['search'] = trim($_POST['search']);
if (is_numeric($_POST['search'])) {
	if (strlen($_POST['search']) == 5) {
		//On récupère les annonces
		if ($_POST['option'] == 'suiv') {
			$aAllAnnonces = getAnnoncesSuivByCP($_POST['search'], $_POST['id_annonce']);
		} else {
			$aAllAnnonces = getAnnoncesPrecByCP($_POST['search'], $_POST['id_annonce']);
			$aAllAnnonces = array_reverse($aAllAnnonces);
		}
		$sTypeSearch = 'CP';
	} else if (strlen($_POST['search']) == 2) {
		//On récupère les annonces
		if ($_POST['option'] == 'suiv') {
			$aAllAnnonces = getAnnoncesSuivByDep($_POST['search'], $_POST['id_annonce']);
		} else {
			$aAllAnnonces = getAnnoncesPrecByDep($_POST['search'], $_POST['id_annonce']);
			$aAllAnnonces = array_reverse($aAllAnnonces);
		}
		$sTypeSearch = 'Dep';
	} else if (strlen($_POST['search']) == 3) {
		if (substr($_POST['search'], 0, 2) == 97) {
			//On récupère les annonces
			if ($_POST['option'] == 'suiv') {
				$aAllAnnonces = getAnnoncesSuivByDep($_POST['search'], $_POST['id_annonce']);
			} else {
				$aAllAnnonces = getAnnoncesPrecByDep($_POST['search'], $_POST['id_annonce']);
				$aAllAnnonces = array_reverse($aAllAnnonces);
			}
			$sTypeSearch = 'Dep';
		} else {
			echo 'error_number';
		}
	} else {
		echo 'error_number';
	}
} else {
	//On récupère les annonces
	if ($_POST['option'] == 'suiv') {
		$aAllAnnonces = getAnnoncesSuivByVille($_POST['search'], $_POST['id_annonce']);
	} else {
		$aAllAnnonces = getAnnoncesPrecByVille($_POST['search'], $_POST['id_annonce']);
		$aAllAnnonces = array_reverse($aAllAnnonces);
	}
	$sTypeSearch = 'Ville';
}

if (isset($aAllAnnonces)) {
	if ($aAllAnnonces) {
		//On récupère les critères du candidat
		$aCriteresCandidat = getCriteresByIdCompte($_POST['id_compte']);
		//On récupère le compte
		$aCompteCandidat = getComptebyId($_POST['id_compte']);
		//On récupère le code postal du candidat
		$iCpCandidat = $aCompteCandidat['cp_compte'];
		//On formate
		$aAnnonces = array();
		$iFirstIdAnnonce = $aAllAnnonces[0]['id_annonce'];
		foreach ($aAllAnnonces as $aAnnonce) {
			$iLastIdAnnonce = $aAnnonce['id_annonce'];
			//On récupère l'entreprise
			$sEntreprise = NULL;
			if ($aAnnonce['nom_entreprise']) {
				$sEntreprise = stripslashes($aAnnonce['nom_entreprise']);
				if (strlen($sEntreprise) > ANNONCE_ENTREPRISE_LENGHT) {
					$sEntreprise = substr($sEntreprise, 0, ANNONCE_ENTREPRISE_LENGHT).'...';
				}
			}
			//On récupère la ville
			$sVille = stripslashes($aAnnonce['ville_annonce']);
			//On slug la ville
			$sVilleSlug = slugify($sVille);
			if (strlen($sVille) > ANNONCE_VILLE_LENGHT) {
				$sVille = substr($sVille, 0, ANNONCE_VILLE_LENGHT).'...';
			}
			//On récupère le titre
			$sTitre = stripslashes($aAnnonce['titre_annonce']);
			if ($aAnnonce['id_code_rome']) {
				//On récupère l'id rome
				$iIdRome = $aAnnonce['id_code_rome'];
				//On récupère le code rome
				$aCodeRome = getCodeRomeById($iIdRome);
				//On récupère le label rome
				$sLabelRome = stripslashes($aCodeRome['label_rome']);
				//On slug le label
				$sTitreSlug = slugify($sLabelRome);
			} else {
				//On slug le titre
				$sTitreSlug = slugify($sTitre);
			}
			if (strlen($sTitre) > ANNONCE_TITRE_LENGHT) {
				//On tronque le titre
				$sTitre = substr($sTitre, 0, ANNONCE_TITRE_LENGHT).'...';
			}
			//On récupère la date de création
			$dDateCreaSlug = date('d-m-Y', strtotime($aAnnonce['date_crea_annonce']));
			if ($aAnnonce['date_valid_annonce']) {
				$dDateCreation = $aAnnonce['date_valid_annonce'];
			} else {
				$dDateCreation = $aAnnonce['date_crea_annonce'];
			}
			$dDateCloture = date('Y-m-d 00:00:00', strtotime($dDateCreation.'+ '.ANNONCE_NB_JOURS_ACTIVE.' days'));
			//On récupère la date d'aujourd'hui
			$dDateNow = date('Y-m-d H:i:s');
			$bPostule = true;
			if ($dDateNow > $dDateCloture) {
				$bPostule = false;
			}
			$dDateCrea = getTime($dDateCreation);
			$bCandidature = false;
			//On récupère les critères de l'annonce
			$aCriteresAnnonce = getCriteresByIdAnnonce($aAnnonce['id_annonce']);
			//On récupère la compatibilité
			$aCompa = getCompaByAnnonceAndCompte($aAnnonce['id_annonce'], $_POST['id_compte']);
			if ($aCompa) {
				$fCompa = $aCompa['compa'];
			} else {
				//On récupère les compatibilité
				$fCompa = getCompaCriteres($aCriteresCandidat, $aCriteresAnnonce);
			}
			//On applique le bonus local
			$fCompa = applyBonusLocal($fCompa, $iCpCandidat, $aAnnonce['cp_annonce']);
			//On applique le malus permis
			$fCompa = applyMalusPermis($fCompa, $_POST['id_compte'], $aAnnonce['permis_annonce']);
			//On lisse la compatibilité
			$fCompa = smoothCompa($fCompa);
			$sPourcentage = $fCompa.'%';
			//On récupère la couleur
			$sColorPourcentage = getCouleurPourcentage($fCompa);
			//On récupère la candidature
			$aCandidature = getCandidature($aAnnonce['id_annonce'], $_POST['id_compte']);
			if ($aCandidature) {
				$bCandidature = true;
			}
			//On récupère le type d'annonce
			$aTypeAnnonce = getTypeAnnonceById($aAnnonce['id_type_annonce']);
			$sTypeAnnonce = stripslashes($aTypeAnnonce['nom_type_annonce']);
			$aAnnonces[] = array(
				'id_annonce' => $aAnnonce['id_annonce'],
				'code' => $aAnnonce['code_annonce'],
				'entreprise' => $sEntreprise,
				'cp' => $aAnnonce['cp_annonce'],
				'ville' => $sVille,
				'titre' => $sTitre,
				'date_crea' => $dDateCrea,
				'type' => $sTypeAnnonce,
				'pourcentage' => $sPourcentage,
				'color_pourcentage' => $sColorPourcentage,
				'candidature' => $bCandidature,
				'postule' => $bPostule,
				'ville_slug' => $sVilleSlug,
				'titre_slug' => $sTitreSlug,
				'date_slug' => $dDateCreaSlug
			);
		}
		$bDisplayResults = true;
		//On recupère les annonces précédentes
		$sFunction = 'getAnnoncesPrecBy'.$sTypeSearch;
		$aAnnoncesPrec = $sFunction($_POST['search'], $iFirstIdAnnonce);
		if ($aAnnoncesPrec) {
			$sDisplayPrec = 'oui';
		}
		//On recupère les annonces suivantes
		$sFunction = 'getAnnoncesSuivBy'.$sTypeSearch;
		$aAnnoncesSuiv = $sFunction($_POST['search'], $iLastIdAnnonce);
		if ($aAnnoncesSuiv) {
			$sDisplaySuiv = 'oui';
		}else{
			$sDisplaySuiv = 'non';
		}
	} else {
		switch ($sTypeSearch) {
			case 'CP' :
			//On récupère la ville
			$aVille = getVilleByCP($_POST['search']);
			$sLocal = stripslashes($aVille['ville']);
			break;
			case 'Dep' :
			$aDep = getDepByNum($_POST['search']);
			$sLocal = stripslashes($aDep['nom_maj_dep']);
			break;
			case 'Ville' :
			$sLocal = $_POST['search'];
			break;
		}
		$sMessage = '
		<div class="text-center">
		<img src="'.IMG.'you_rock.gif" alt="Tu l\'as fait !" class="visible-xs margin-auto img-responsive" />
		<img src="'.IMG.'you_rock.gif" alt="Tu l\'as fait !" class="hidden-xs col-sm-offset-5 col-sm-2 img-responsive" />
		<div class="lead col-sm-12 bleuClair marginTop20">
		'.$sLocal.' ?<br class="visible-xs" />
		Aucune annonce pour l\'instant ?<br />
		En attendant, viens voir ce qu\'on fait sur tes réseaux sociaux préférés !<br /><br />
		<a href="https://www.facebook.com/uprigs/" title="Facebook Uprigs" target="_blank" class="mt-facebook mt-share-inline-square-bw-sm">
		<img src="'.IMG.'facebook.png" />
		</a>
		<a href="https://twitter.com/UprigsJob" title="Twitter Uprigs" target="_blank" class="mt-twitter mt-share-inline-square-bw-sm">
		<img src="'.IMG.'twitter.png" />
		</a>
		</div>
		</div>
		';
		echo $sMessage;
	}
}

//Ne pas laisser d'espaces
?>
<?php if ($bDisplayResults) : ?>

	<table class="table">
		<?php foreach ($aAnnonces as $aAnnonce) : ?>
			<tr>
				<td class="lineHeight25 paddingTop20 paddingBottom20 col-xs-12 col-lg-7 grisFonce m-text-center s-text-center d-text-center">
					<a href="<?= URL.$aAnnonce['ville_slug'].'/'.$aAnnonce['titre_slug'].'/'.$aAnnonce['date_slug'].'/'.$aAnnonce['code'] ?>/" title="<?= $aAnnonce['titre'] ?>">
						<span class="fontSize18"><b><?= $aAnnonce['titre'] ?></b></span>
					</a><br />
					<b><?= $aAnnonce['entreprise'] ?></b><br class="visible-xs" />
					<?= $aAnnonce['cp'] ?> <?= $aAnnonce['ville'] ?><br />
					<?php if($aAnnonce['postule']){ ?>
						Il y a <?= $aAnnonce['date_crea'] ?> -
					<?php }else{ ?>
						Annonce cloturée -
					<?php } ?>
					<?= $aAnnonce['type'] ?>
				</td>
				<td class="td-compa-recherche col-xs-12 col-lg-2 text-center d-text-center s-td-no-border m-td-no-border d-td-no-border">
					<span class="hidden-lg"><span class="grisFonce">Ta compatibilité</span><br /></span>
					<b class="fontSize35 <?= $aAnnonce['color_pourcentage'] ?>"><?= $aAnnonce['pourcentage'] ?></b>
				</td>
				<td class="col-xs-12 col-sm-offset-2 col-sm-8 col-lg-offset-0 col-lg-3 text-center grisFonce m-td-no-border s-td-no-border d-td-no-border">
					<?php if ($aAnnonce['candidature']) : ?>
						<div class="div-felicitations-recherche">
							<span class="lead">Félicitations !</span><br />
							Candidature transmise.
						</div>
					<?php else : ?>
						<?php if ($aAnnonce['postule']) : ?>
							<a href="<?= URL.$aAnnonce['ville_slug'].'/'.$aAnnonce['titre_slug'].'/'.$aAnnonce['date_slug'].'/'.$aAnnonce['code'] ?>/" title="<?= $aAnnonce['titre'] ?>" class="btn btn-inverse btn-postuler-recherche height40 col-xs-12">
								<span class="col-xs-9 btn-text-left">Voir l'annonce</span>
								<i class="fa fa-dot-circle-o col-xs-3 paddingTop3" aria-hidden="true"></i>
							</a>
							<div id="div-postule-<?= $aAnnonce['id_annonce'] ?>" class="hidden"></div>
						<?php else : ?>
							<div class="div-no-postule">
								<i>Tu arrives trop tard <br />
									pour cette annonce.</i>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if($sDisplaySuiv == 'non'){ ?>
				<tr>
					<td>
						<div class="col-sm-12 bgBleuClair radius5">
							<div class="col-md-12 lead text-center paddingTop30 paddingLeft10 paddingRight10 paddingBottom20">
								<span class="fontSize30"><b>Et maintenant ?</b></span><br />
								Au tour d'<b>Uprigs</b> de travailler.<br />
								<b>Surveille tes emails</b>, tu en reçois dès qu'on a une offre pour toi.
							</div>
						</div>
					</td>
				</tr>
			<?php } ?>
		</table>

		<input type="hidden" id="idFirstAnnonce" value="<?= $iFirstIdAnnonce ?>" />
		<input type="hidden" id="idLastAnnonce" value="<?= $iLastIdAnnonce ?>" />
		<input type="hidden" id="displayPrec" value="<?= $sDisplayPrec ?>" />
		<input type="hidden" id="displaySuiv" value="<?= $sDisplaySuiv ?>" />

	<?php endif; ?>
