<?php

include_once '../../../config/constantes.php';
include_once CONTROLLERS.'functions.php';

// set filters
$sCareer = 'Lawyer';
$iGender = $_POST['iGender'];
$iMinAge = $_POST['iMinAge'];
$iMaxAge = $_POST['iMaxAge'];

$aGender = array();
if($iGender == 1){
  $aGender[] = 'Femme';
}else if($iGender == 2){
  $aGender[] = 'Homme';
}else{
  $aGender[] = 'Femme';
  $aGender[] = 'Homme';
}

// open file and make an array
$aData = csv_to_array('http://thomas-g.fr/web/ressources/data.csv', ';');

$iNbPeople = array();
$iNbPeople['Homme'] = 0;
$iNbPeople['Femme'] = 0;

for ($i=0; $i < 21 ; $i++) {
  $aResult[$i]['Homme'] = array();
  $aResult[$i]['Femme'] = array();
}

foreach ($aData as $aPeople) {
  $iAge = $aPeople['age'];
  $sGender = $aPeople['gender'];
  $sPeopleCareer = $aPeople['career'];

  if(in_array($sGender, $aGender) && $sPeopleCareer == $sCareer) {
    if($iAge > $iMinAge && $iAge < $iMaxAge) {

      $iExpNum = $aPeople['expnum'];
      $aResult[$iExpNum][$sGender][] = true;

      $iNbPeople[$sGender]++;
    }
  }
}

if($iNbPeople['Femme'] == 0 && $iNbPeople['Homme'] == 0){
  $aFinalResult = array();
  for ($i=0; $i < 21 ; $i++) {
    $aFinalResult[$i]['Femme'] = 0;
    $aFinalResult[$i]['Homme'] = 0;
  }
}else if($iNbPeople['Femme'] == 0){
  $aFinalResult = array();
  for ($i=0; $i < 21 ; $i++) {
    $aFinalResult[$i]['Femme'] = 0;
    $aFinalResult[$i]['Homme'] = round((count($aResult[$i]['Homme']) / $iNbPeople['Homme']) * 50);
  }
}else if($iNbPeople['Homme'] == 0){
  $aFinalResult = array();
  for ($i=0; $i < 21 ; $i++) {
    $aFinalResult[$i]['Femme'] = round((count($aResult[$i]['Femme']) / $iNbPeople['Femme']) * 50);
    $aFinalResult[$i]['Homme'] = 0;
  }
}else{
  $aFinalResult = array();
  for ($i=0; $i < 21 ; $i++) {
    $aFinalResult[$i]['Femme'] = round((count($aResult[$i]['Femme']) / $iNbPeople['Femme']) * 50);
    $aFinalResult[$i]['Homme'] = round((count($aResult[$i]['Homme']) / $iNbPeople['Homme']) * 50);
  }
}


$aHtml = array();
foreach ($aFinalResult as $iColumn => $aColumn) {
  $aHtml[$iColumn] = '';
  foreach ($aColumn as $key => $value) {
    if($key =='Femme'){
      $aHtml[$iColumn] .= str_repeat("<div class='bullet women'><div></div></div>", $aFinalResult[$iColumn]['Femme']);
    }else if($key =='Homme'){
      $aHtml[$iColumn] .= str_repeat("<div class='bullet men'><div></div></div>", $aFinalResult[$iColumn]['Homme']);
    }
  }
}

echo json_encode($aHtml);
