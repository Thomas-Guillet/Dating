<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'annonces_postulants.php';
include_once MODELS.'comptes.php';
include_once MODELS.'mails.php';
include_once MODELS.'annonces_criteres.php';
include_once MODELS.'comptes_criteres.php';
include_once MODELS.'criteres.php';
include_once MODELS.'annonces.php';
include_once MODELS.'comptes_permis.php';
include_once MODELS.'comptes_atouts.php';
include_once MODELS.'liste_atouts.php';
include_once MODELS.'compatibilites.php';
include_once MODELS.'comptes_inconvenients.php';
include_once MODELS.'liste_annonce_avantages_inconvenients.php';
include_once CONTROLLERS.'algo.php';

//On récupère l'id candidat
$iIdCandidat = $_POST['candidat'];
//On récupère le candidat
$aCandidat = getCandidatById($iIdCandidat);
//On récupère le compte
$aCompte = getComptebyId($aCandidat['id_compte']);
//On récupère le code postal du candidat
$iCpCandidat = $aCompte['cp_compte'];
//On récupère le téléphone
$sTel = chunk_split($aCompte['tel_compte'], 2, ' ');
//On récupère le mail
$aMail = getMailById($aCompte['id_mail']);
$sMail = stripslashes($aMail['mail']);
//On récupère le code postal
$sCP = $aCompte['cp_compte'];
$dDateCandidature = NULL;
$aCriteresCandidatOk = NULL;
$aCriteresCandidatNotOk = NULL;
//On récupère la date de candidature
$dDateCandidature = date('d-m-Y H:i', strtotime($aCandidat['date_candidature']));

//On récupère l'annonce
$aAnnonce = getAnnonceById($aCandidat['id_annonce']);
//On récupère le code postal
$iCPAnnonce = $aAnnonce['cp_annonce'];

//On récupère les inconvénients
$aInconvenientsAnnonce = NULL;
if ($aAnnonce['ids_inconvenients']) {
	$aInconvenientsAnnonce = unserialize($aAnnonce['ids_inconvenients']);
}

//On récupère les annonces du compte
$aAnnoncesCompte = getAnnoncesActivesByIdCompte($aAnnonce['id_compte']);
$sIdsAnnoncesCompte = '';
//On récupère les ids annonces
foreach ($aAnnoncesCompte as $aAnnonceCompte) {
	$sIdsAnnoncesCompte .= $aAnnonceCompte['id_annonce'].',';
}
$sIdsAnnoncesCompte = substr($sIdsAnnoncesCompte, 0, strlen($sIdsAnnoncesCompte)-1);
$sIdsAnnoncesCompte = '('.$sIdsAnnoncesCompte.')';

//On récupère le nombre de candidature
$aNbCandidatures = getNbCandidatureByAnnoncesAndCompte($sIdsAnnoncesCompte, $aCandidat['id_compte']);
$iNbCandidatures = $aNbCandidatures['nb_candidatures'];
$iNbCandidatures--;
//On récupère les x dernières candidatures
$aAllLastCandidatures = getLastCandidaturesByAnnoncesAndCompte($sIdsAnnoncesCompte, $aCandidat['id_compte'], ANNONCE_CONTACT_NB_ANNONCES_DISPLAY);
$sLastCandidatures = '';
foreach ($aAllLastCandidatures as $aLastCandidature) {
	//On récupère le titre
	$aLastAnnonce = getAnnonceById($aLastCandidature['id_annonce']);
	$sTitreLastAnnonce = stripslashes($aLastAnnonce['titre_annonce']);
	//On récupère la date
	$dDateLastAnnonce = date('d-m-Y', strtotime($aLastCandidature['date_candidature']));
	$sLastCandidatures .= 'Le '.$dDateLastAnnonce.' : '.$sTitreLastAnnonce.' - ';
}
$sLastCandidatures = substr($sLastCandidatures, 0, strlen($sLastCandidatures)-3);
//On récupère les critères
$aAllCriteres = getCriteres();
//On formate
$aCriteres = array();
foreach ($aAllCriteres as $aCritere) {
	$aCriteresTmp = array();
	for ($i=1; $i<=3; $i++) {
		$aCriteresTmp[$i] = stripslashes($aCritere['phrase'.$i]);
	}
	$aCriteres[$aCritere['id_critere']] = $aCriteresTmp;
}
//On récupère les critères de l'annonce
$aCriteresAnnonce = getCriteresByIdAnnonce($aCandidat['id_annonce']);
//On récupère les critères du candidat
$aIdsCriteresCandidat = getCriteresByIdCompte($aCandidat['id_compte']);
if ($aIdsCriteresCandidat) {
	$aCriteresCandidatOk = array();
	$aCriteresCandidatMiddle = array();
	$aCriteresCandidatNotOk = array();
	for ($i=1; $i<=CRITERES_NB; $i++) {
		$aCriteresCandidat[$i] = $aCriteres[$i][$aIdsCriteresCandidat['critere'.$i]];
		if ($aIdsCriteresCandidat['critere'.$i] == $aCriteresAnnonce['critere'.$i]) {
			$aCriteresCandidatOk[$i] = $aCriteres[$i][$aIdsCriteresCandidat['critere'.$i]];
		} else {
			if (($aIdsCriteresCandidat['critere'.$i] == 2) || ($aCriteresAnnonce['critere'.$i] == 2)) {
				$aCriteresCandidatMiddle[$i] = $aCriteres[$i][$aIdsCriteresCandidat['critere'.$i]];
			} else {
				$aCriteresCandidatNotOk[$i] = $aCriteres[$i][$aIdsCriteresCandidat['critere'.$i]];
			}
		}
	}
}

$sPermis = NULL;
//On récupère le permis
$aPermis = getPermisByIdCompte($aCandidat['id_compte']);
if ($aPermis) {
	$sPermis = $aPermis['etat_permis'];
}

//On récupère la proximité
$sProximite = NULL;
if ($iCpCandidat == $iCPAnnonce) {
	$sProximite = 'CP';
} else {
	$iDepCandidat = substr($iCpCandidat, 0, 2);
	$iDepAnnonce = substr($iCPAnnonce, 0, 2);
	if ($iDepCandidat == $iDepAnnonce) {
		$sProximite = 'DEP';
	}
}

//On récupère les inconvenients
$aIdsInconvenientsCompte = getInconvenientsByIdCompte($aCandidat['id_compte']);
$aInconvenientsCompte = array();
foreach ($aIdsInconvenientsCompte as $aIdInc) {
	//On récupère l'id inconvénient
	$iIdInc = $aIdInc['id_inc'];
	//On récupère l'inconvénient
	$aInconvenient = getAvIncById($iIdInc);
	$sInconvenient = stripslashes($aInconvenient['nom_av_inc']);
	if ($aInconvenientsAnnonce) {
		if (in_array($iIdInc, $aInconvenientsAnnonce)) {
			$aInconvenientsCompte[] = array(
				'inconvenient' => $sInconvenient,
				'annonce' => true
			);
		} else {
			$aInconvenientsCompte[] = array(
				'inconvenient' => $sInconvenient,
				'annonce' => false
			);
		}
	}
}

//On récupère la compatibilité
$aCompa = getCompaByAnnonceAndCompte($aCandidat['id_annonce'], $aCandidat['id_compte']);
if ($aCompa) {
	$fCompa = $aCompa['compa'];
} else {
	//On récupère les compatibilité
	$fCompa = getCompaCriteres($aIdsCriteresCandidat, $aCriteresAnnonce);
}
//On applique le bonus local
$fCompa = applyBonusLocal($fCompa, $iCpCandidat, $iCPAnnonce);
//On applique le malus permis
if ($aAnnonce['permis_annonce'] == 'oui') {
	$bPermis = false;
	if ($aPermis && ($aPermis['etat_permis'] == 'oui')) {
		$bPermis = true;
	}
	if (!$bPermis) {
		$fCompa -= 6;
	}
}
//On lisse la compatibilité
$fCompa = smoothCompa($fCompa);
//On récupère la couleur
$sColor = getCouleurPourcentage($fCompa);
$sHexa = getHexaPourcentage($fCompa);

//On récupère l'état du candidat
$sEtat = stripslashes($aCandidat['etat_candidature']);
$bEtat = false;
$sValueStatut = "passe";
switch ($sEtat) {
	case 'valide' :
		$bEtat = true;
		$sEtatDisplay = 'Il m\'interresse';
		$sEtatColor = 'text-success';
		$sValueStatut = "valide";
		break;
	case 'invalide' :
		$bEtat = true;
		$sEtatDisplay = 'Il ne m\'intéresse pas';
		$sEtatColor = 'text-danger';
		$sValueStatut = "invalide";
		break;
}

$aAtouts = array();
$aIdAtouts = getAtoutsByIdCompte($aCandidat['id_compte']);
if(count($aIdAtouts) > 0){
	foreach ($aIdAtouts as $aIdAtout) {
		$aNomAtout = getAtoutById($aIdAtout['id_atout']);
		$aAtouts[] = stripslashes($aNomAtout['nom_atout']);
	}
}

?>

<div class="text-center">
	<button type="button" class="btn btn-link paddingLeft20 paddingRight20" id="btn-liste-1">Voir la liste complète des candidats <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></button>
</div><br />
<div class="letter paddingBottom40 paddingTop20">
	<div id="aide8">
		<div id="post-it" class="quote-container hidden-xs">
			<i class="pin"></i>
			<blockquote class="note yellow">
				<cite class="author">
					<p class="grisFonce text-center">
						<b>
							<span class="glyphicon glyphicon-time fontSize20" aria-hidden="true"></span><br />
							Date de candidature<br /><br />
						</b><br class="visible-xs" />
						<?= $dDateCandidature ?>
					</p>
				</cite>
			</blockquote>
		</div>
		<div class="paddingBottom25 paddingLeft50 paddingRight50 div-info-candidat">
			<div class="row">
				<div class="col-sm-4 col-md-3 text-center bleuClair">
					<p class="borderBleuClair border8 radius20 paddingTop30 paddingBottom30">
						<b>Fiche<br />
						Candidat</b>
					</p>
				</div>
				<br class="visible-xs" />
				<div class="col-sm-8 col-md-9 grisFonce">
					<p>
						<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> <?= $sTel ?>
					</p>
					<p>
						<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> <?= $sMail ?>
					</p>
					<p>
						<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <?= $sCP ?>
					</p>
					<?php if ($sPermis) : ?>
						<?php if ($sPermis == 'oui') : ?>
							<p class="text-success">
								<i class="fa fa-car" aria-hidden="true"></i>
								J'ai le permis.
							</p>
						<?php else : ?>
							<p class="text-danger">
								<i class="fa fa-car" aria-hidden="true"></i>
								Je n'ai pas le permis.
							</p>
						<?php endif; ?>
					<?php endif; ?>
					<p class="visible-xs">
						<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= $dDateCandidature ?>
					</p>
				</div>
			</div>
			<?php if ($iNbCandidatures > 0) : ?>
				<br />
				<span class="grisFonce tooltip-annonces-candidats pointer" data-toggle="tooltip" data-placement="right" title="<?= $sLastCandidatures ?>">
					J'ai postulé sur <span class="bleuClair"><b><?= $iNbCandidatures ?></b></span> de vos annonces.
				</span>
			<?php endif; ?>
		</div>
	</div>
	<?php if ($aAtouts) : ?>
		<div id="aide9" class="row div-atouts-candidature">
			<div class="col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6">
				<p class="fontSize16 head-atouts">
					<b>
						<?php if (count($aAtouts) == 1) : ?>
							L'atout de mon profil
						<?php else : ?>
							Les atouts de mon profil
						<?php endif; ?>
					</b>
				</p>
				<div class="row">
					<?php foreach ($aAtouts as $sAtout) : ?>
						<div class="col-sm-6">
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							<?= $sAtout ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<br /><hr class="borderTopBleu5" />
	<?php endif; ?>
	<div id="aide10" class="row marginTop40 paddingTop25 paddingBottom25 paddingLeft50 paddingRight50">
		<div class="col-sm-4">
			<input type="hidden" id="containerNumberC" value="<?= $fCompa ?>" />
			<input type="hidden" id="containerHexaC" value="<?= $sHexa ?>" />
			<div id="divPourcent" class="containerPourcentage"></div>
		</div>
		<div class="visible-xs"><br /><br /></div>
		<div class="col-sm-8 div-criteres">
			<p class="lead grisFonce"><b>Compatibilité avec le poste</b></p>
			<?php if ($sProximite == 'CP') : ?>
				<p class="text-success">
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
					Je suis sur le même code postal que le poste proposé.
				</p>
			<?php elseif ($sProximite == 'DEP') : ?>
				<p class="text-success">
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
					Je suis dans le même département que le poste proposé.
				</p>
			<?php endif; ?>
			<?php if ($aCriteresCandidatOk) : ?>
				<?php foreach ($aCriteresCandidatOk as $iCritere =>  $sCritere) : ?>
					<p class="text-success">
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?= $sCritere ?>
					</p>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ($aCriteresCandidatMiddle) : ?>
				<?php foreach ($aCriteresCandidatMiddle as $iCritere =>  $sCritere) : ?>
					<p class="text-warning">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <?= $sCritere ?>
					</p>
				<?php endforeach; ?>	
			<?php endif; ?>
			<?php if ($aCriteresCandidatNotOk) : ?>
				<?php foreach ($aCriteresCandidatNotOk as $iCritere =>  $sCritere) : ?>
					<p class="text-danger">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?= $sCritere ?>
					</p>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php if ($aInconvenientsCompte) : ?>
		<hr class="hr80 borderTopBleu" />
		<div id="aide11" class="row paddingTop25 div-inc paddingLeft50 paddingRight50">
			<div class="col-sm-offset-1 col-sm-10 grisFonce">
				<p class="div-inc-head">
					<?php if (count($aInconvenientsCompte) == 1) : ?>
						En bonus, voici la spécificité de poste qui j'ai accepté avant de découvrir votre annonce :
					<?php else : ?>
						En bonus, voici les spécificités de poste qui j'ai accepté avant de découvrir votre annonce :
					<?php endif; ?>
				</p><br />
				<?php if (count($aInconvenientsCompte) == 1) : ?>
					<?php foreach ($aInconvenientsCompte as $aInconvenient) : ?>
						<?php if ($aInconvenient['annonce']) : ?>
							<p class="bleuClair text-center">
						<?php else : ?>
							<p class="text-center">
						<?php endif; ?>
								<i class="fa fa-circle" aria-hidden="true"></i>
								<?= $aInconvenient['inconvenient'] ?>
							</p>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="row">
						<?php foreach ($aInconvenientsCompte as $aInconvenient) : ?>
							<?php if ($aInconvenient['annonce']) : ?>
								<div class="col-sm-6 bleuClair marginBottom10">
							<?php else : ?>
								<div class="col-sm-6 marginBottom10">
							<?php endif; ?>
									<i class="fa fa-circle" aria-hidden="true"></i>
									<?= $aInconvenient['inconvenient'] ?>
								</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>

<div id="aide12" class="text-center marginTop50">
	<p class="lead grisFonce">
		<span class="paddingLeft20 paddingRight20">
			<?php if ($bEtat) : ?>
				Vous avez statué ce candidat :
				<br class="visible-xs"/>
				<span class="<?= $sEtatColor ?>"><b><?= $sEtatDisplay ?></b></span>
			<?php else : ?>
				Vous n'avez pas statué ce candidat.
			<?php endif; ?>
		</span>
	</p>
	<button type="button" class="btn btn-lg btn-primary col-sm-offset-4 col-sm-4 paddingLeft20 paddingRight20 marginBottom20" data-toggle="modal" data-target="#modaleStatut">Modifier le statut</button><br />
</div>

<div class="visible-lg">
	<ol id="joyRideCandidat">
		<li data-id="aide8" data-text="Suivant" data-options="tipLocation:right;tipAnimation:fade">
			<h2>Votre candidat</h2>
			<br />
			<p>Vous retrouvez ici les informations de votre candidat : coordonnées, permis, date de candidature...</p>
			<br />
		</li>
		<li data-id="aide9" data-text="Suivant" data-options="tipLocation:right;tipAnimation:fade">
			<h2>Les atouts</h2>
			<br />
			<p>
				Les atouts sont les points forts du candidat.
				Plus un candidat est actif sur Uprigs, plus il peut choisir d'atouts.
				Voici ceux choisis par votre candidat pour se décrire.
			</p>
			<br />
		</li>
		<li data-id="aide10" data-text="Suivant" data-options="tipLocation:right;tipAnimation:fade">
			<h2>La compatibilité</h2>
			<br />
			<p>
				Grâce au taux de compatibilité, vous pouvez estimer en un coup d'oeil le potentiel de ce candidat avec votre annonce.
				En prime, vous avez accès aux préférences de votre candidat pour son futur job.
				Elles sont triées de façon à ce que vous remarquiez tout de suite ce qui correspond avec votre annonce.
			</p>
			<br />
		</li>
		<li data-id="aide11" data-text="Suivant" data-options="tipLocation:right;tipAnimation:fade">
			<h2>Les spécificités</h2>
			<br />
			<p>
				Découvrez ici les spécificités que votre candidat est prêt à accepter pour son job.
				Si vous avez indiqué des spécificités lors de votre dépôt d'annonce et qu'elles correspondent avec le profil de votre candidat, elles apparaîtront en bleu.
			</p>
			<br />
		</li>
		<li data-id="aide12" data-text="Suivant" data-options="tipLocation:right;tipAnimation:fade">
			<h2>Le statut</h2>
			<br />
			<p>Retrouvez ici le statut de votre candidat. Pour le modifier, il vous suffit de cliquer sur le bouton.</p>
			<br />
		</li>
		<li data-id="btn-liste-1" data-text="Suivant" data-options="tipLocation:right;tipAnimation:fade">
			<h2>Liste complète</h2>
			<br />
			<p>Cliquez ici pour accéder à la liste complète de vos candidats.</p>
			<br />
		</li>
		<li data-id="div-joyride-help" data-text="Suivant" data-options="tipLocation:right;tipAnimation:fade">
			<h2>Besoin d'aide ?</h2>
			<br />
			<p>Si vous cliquez ici, je reviendrai à nouveau vous présenter votre outil.</p>
			<br />
		</li>
		<li data-button="Fermer">
			<h2>C'est parti !</h2>
			<br />
			<p>Maintenant, à vous de jouer.</p>
			<br />
		</li>
	</ol>
</div>

<!-- Modale statuts -->
<div class="modal fade" id="modaleStatut" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title lead text-center bleuClair">Modification du statut</h4>
			</div>
			<div class="modal-body text-center">
				<form action="#" method="post">
					<input type="hidden" name="id_candidat" value="<?= $iIdCandidat ?>" />
					<input type="hidden" name="liste" value="true" />
					<button type="submit" name="valider" class="btn btn-lg btn-success btn-shadow col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8">
						<div class="col-xs-10 text-left paddingLeft20">
							Il m'intéresse
						</div>
						<div class="col-xs-2">
							<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
						</div>
					</button>
					<br class="visible-xs" />
					<button type="submit" name="invalider" class="btn btn-lg btn-danger btn-shadow col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 marginTop20">
						<div class="col-xs-10 text-left paddingLeft20">
							Il ne m'intéresse pas
						</div>
						<div class="col-sm-2">
							<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
						</div>
					</button>
					<br class="visible-xs" />
					<button type="submit" name="passer" class="btn btn-lg btn-default btn-shadow col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 marginTop20 marginBottom50">
						<div class="col-xs-10 text-left paddingLeft20">
							Je m'en occuperai plus tard
						</div>
						<div class="col-xs-2">
							<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
						</div>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {

	if ($('#containerHexaC').val()) {
		var bar = new ProgressBar.Circle(divPourcent, {
			strokeWidth: 4,
			easing: 'easeInOut',
			duration: 1400,
			text: {
				autoStyleContainer: false
			},
			color: $('#containerHexaC').val(),
			trailColor: 'white',
			trailWidth: 1,
			svgStyle: null,
			from: {color: $('#containerHexaC').val(), a:0},
			to: {color: $('#containerHexaC').val(), a:1},
			step: function(state, circle) {
			circle.path.setAttribute('stroke', state.color);
			var value = Math.round(circle.value() * 100);
				if (value <= $('#containerNumberC').val()) {
					circle.setText(value+'%');
				} else {
					circle.setText($('#containerNumberC').val()+'%');
					return;
				}
			}
		});
		bar.text.style.fontSize = '5rem';
		if ($('#containerNumberC').val() == 100) {
			var pourcentage = 1.0;
		} else {
			var pourcentage = '0.'+$('#containerNumberC').val();
		}
		pourcentage = parseFloat(pourcentage);
		bar.animate(pourcentage);
	}

	$('#btn-liste-1').click(function() {
		$('#candidat').slideUp('slow', function() {
			$('#liste').slideDown('slow');
		});
	});

	$('#joyride_help').click(function(){
		if ($('#candidat').css('display') == 'block') {
			$('#joyRideCandidat').joyride({ 
				autoStart : true,
				modal:true,
				expose: true,
				postRideCallback: function() {
					$('.first-joyride-tips').joyride('destroy');
				}
			});
		}
	});

});
</script>
