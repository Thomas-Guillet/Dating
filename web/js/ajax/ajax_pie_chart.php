<?php

include_once '../../../config/constantes.php';
include_once CONTROLLERS.'functions.php';

// set filters
$sCareer = $_POST['sCareer'];
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

// set goals
$sGoal1 = 'Seemed like a fun night out';
$sGoal2 = 'To meet new people';
$sGoal3 = 'To say « i did it »';
$sGoal4 = 'To get a date';
$sGoal5 = 'Looking for a serious relationship';
$sGoal6 = 'Other';

$aResults = array();
$aResults[1]['title'] = $sGoal1;
$aResults[1]['nbMale'] = 0;
$aResults[1]['nbFemale'] = 0;
$aResults[1]['title'] = $sGoal1;
$aResults[1]['nbMale'] = 0;
$aResults[1]['nbFemale'] = 0;
$aResults[2]['title'] = $sGoal2;
$aResults[2]['nbMale'] = 0;
$aResults[2]['nbFemale'] = 0;
$aResults[3]['title'] = $sGoal3;
$aResults[3]['nbMale'] = 0;
$aResults[3]['nbFemale'] = 0;
$aResults[4]['title'] = $sGoal4;
$aResults[4]['nbMale'] = 0;
$aResults[4]['nbFemale'] = 0;
$aResults[5]['title'] = $sGoal5;
$aResults[5]['nbMale'] = 0;
$aResults[5]['nbFemale'] = 0;
$aResults[6]['title'] = $sGoal6;
$aResults[6]['nbMale'] = 0;
$aResults[6]['nbFemale'] = 0;
$iNbTotal = 0;
$iNbTotalM = 0;
$iNbTotalF = 0;

// open file and make an array
$aData = csv_to_array('http://thomas-g.fr/web/ressources/data.csv', ';');

foreach ($aData as $aPeople) {
  $iAge = $aPeople['age'];
  $sGender = $aPeople['gender'];
  $sPeopleCareer = $aPeople['career'];
  $sPeopleGoal = $aPeople['goal'];

  if(in_array($sGender, $aGender) && $sPeopleCareer == $sCareer) {
    if($iAge >= $iMinAge && $iAge <= $iMaxAge) {

      if($sPeopleGoal == $sGoal1){
        $iIdGoal = 1;
      }else if($sPeopleGoal == $sGoal2){
        $iIdGoal = 2;
      }else if($sPeopleGoal == $sGoal3){
        $iIdGoal = 3;
      }else if($sPeopleGoal == $sGoal4){
        $iIdGoal = 4;
      }else if($sPeopleGoal == $sGoal5){
        $iIdGoal = 5;
      }else if($sPeopleGoal == $sGoal6){
        $iIdGoal = 6;
      }

      if($sGender == 'Homme'){
        $aResults[$iIdGoal]['nbMale']++;
        $iNbTotalM++;
      }else{
        $aResults[$iIdGoal]['nbFemale']++;
        $iNbTotalF++;
      }
      $iNbTotal++;
    }
  }
}

$aFinalResults = array();
foreach ($aResults as $key => $value) {
  $iPercMaleGoal = 0;
  $iPercFemaleGoal = 0;
  $iPercTotalGoal = 0;
  $iTotalGoal = $value['nbMale'] + $value['nbFemale'];
  if($iTotalGoal != 0){
    $iPercMaleGoal = ( $value['nbMale'] / $iTotalGoal ) * 100;
    $iPercFemaleGoal = ( $value['nbFemale'] / $iTotalGoal ) * 100;
    $iPercTotalGoal = ($iTotalGoal / $iNbTotal) * 100;
  }

  $aFinalResults[$key]['Total'] = round($iPercTotalGoal);
  $aFinalResults[$key]['M'] = round($iPercMaleGoal);
  $aFinalResults[$key]['F'] = round($iPercFemaleGoal);
}

// var_dump($aFinalResults);
echo json_encode($aFinalResults);
