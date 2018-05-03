<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'liste_codes_rome.php';
include_once MODELS.'liste_codes_rome_tags.php';
include_once CONTROLLERS.'fonctions.php';

$sValue = $_POST['sValue'];
$sValue = preg_replace('!\s+!', ' ', $sValue);
$aValue = explode(' ', trim($sValue));
//On formate
$aSearch = array();
foreach ($aValue as $sVal) {
  if (strlen($sVal) > 2) {
    $aSearch[] = $sVal;
  }
}

$aMetiers = array();
$aIdsRome = array();

//On récupère les métiers
$aAllMetiers = getCodeRomeBySearch($sValue);
if (count($aAllMetiers) < 3) {
  foreach ($aAllMetiers as $aMetier) {
    //On récupère l'id rome
    $iIdRome = $aMetier['id_rome'];
    $aIdsRome[] = $iIdRome;
    //On récupère le métier
    $sMetier = stripslashes($aMetier['label_titre']);
    $aMetiers[] = array(
        'id_rome' => $iIdRome,
        'label_titre' => $sMetier
      );
  }
}


//On récupère les ids tags
$aAllTags = getTagsByLabels($aSearch);
if ($aAllTags) {
  //On formate
  $aTags = array();
  foreach ($aAllTags as $aTag) {
    //On récupère l'id tag
    $iTag = $aTag['tag'];
    $aTags[] = $iTag;
  }
  //On récupère les métiers
  if ($aIdsRome) {
    $sIdsRome = implode(',', $aIdsRome);
    $aAllMetiers = getMetiersByTags($aTags, $sIdsRome);
  } else {
    $aAllMetiers = getMetiersByTags($aTags);
  }
  foreach ($aAllMetiers as $aMetier) {
    //On récupère l'id rome
    $iIdRome = $aMetier['id_rome'];
    $aIdsRome[] = $iIdRome;
    //On récupère le métier
    $sMetier = stripslashes($aMetier['label_titre']);
    $aMetiers[] = array(
      'id_rome' => $iIdRome,
      'label_titre' => $sMetier
    );
  }
}

if ($aIdsRome) {
  $sIdsRome = implode(',', $aIdsRome);
  $aAllMetiers = getMetiersByLabels($aSearch, $sIdsRome);
} else {
  $aAllMetiers = getMetiersByLabels($aSearch);
}
foreach ($aAllMetiers as $aMetier) {
  //On récupère l'id rome
  $iIdRome = $aMetier['id_rome'];
  //On récupère le métier
  $sMetier = stripslashes($aMetier['label_titre']);
  $aMetiers[] = array(
      'id_rome' => $iIdRome,
      'label_titre' => $sMetier
    );
}

$sHtml = '<ul id="blue-scroll-x" class="typeahead dropdown-menu" role="listbox" style="left: 15px; display: block; width: 100%; max-height: 200px; overflow: scroll;">';
if(count($aMetiers) != 0){

  foreach ($aMetiers as $value) {
    $sHtml .= '<li id="result-rome-'.$value['id_rome'].'" data-value="'.$value['label_titre'].'" data-id="'.$value['id_rome'].'"><a class="dropdown-item" role="option">'.$value['label_titre'].'</li>';
  }

}else{
  $sHtml .= '<li class=""><a class="dropdown-item" href="#" role="option">Aucun résultat</li>';
}
$sHtml .= '</ul>';

echo $sHtml;

?>
