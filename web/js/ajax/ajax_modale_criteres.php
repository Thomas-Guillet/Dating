<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'criteres.php';

//On récupère les critères
$aAllCriteres = getCriteres();
//On formate
$aCriteres = array();
foreach ($aAllCriteres as $aCritere) {
  //On récupère la phrase 1
  $sPhrase1 = stripslashes($aCritere['phrase1']);
  //On récupère la phrase 2
  $sPhrase2 = stripslashes($aCritere['phrase2']);
  //On récupère la phrase 3
  $sPhrase3 = stripslashes($aCritere['phrase3']);
  $iPos = rand(1,3);
  $aCriteres[] = array(
    'id' => $aCritere['id_critere'],
    'phrase1' => $sPhrase1,
    'phrase2' => $sPhrase2,
    'phrase3' => $sPhrase3,
    'pos' => $iPos
  );
}
//On récupère le compte
$iIdCompte = $_SESSION[SESSION]['compte'];

?>

<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h3 class="modal-title">Ton job idéal</h3>
    </div>
    <div class="modal-body">
      <p class="text-center grisFonce lead">Déplace les curseurs de gauche à droite et choisi les phrases qui te correspondent le mieux.</p>
      <div class="row">
        <?php foreach ($aCriteres as $aCritere) : ?>
          <div class="slider-label text-center col-xs-12" crit="<?= $aCritere['id'] ?>">
            <span id="phrase1-<?= $aCritere['id'] ?>" class="bleuClair"><?= $aCritere['phrase1'] ?></span>
            <span id="phrase2-<?= $aCritere['id'] ?>" class="bleuClair"><?= $aCritere['phrase2'] ?></span>
            <span id="phrase3-<?= $aCritere['id'] ?>" class="bleuClair"><?= $aCritere['phrase3'] ?></span>
            <input type="hidden" id="critere<?= $aCritere['id'] ?>" name="critere<?= $aCritere['id'] ?>" class="pos" value="<?= $aCritere['pos'] ?>" /><br />
            <div id="slider<?= $aCritere['id'] ?>" class="slider col-xs-offset-1 col-xs-10 col-sm-offset-3 col-sm-6 col-md-offset-2 col-md-8 col-lg-offset-1 col-lg-10"></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="modal-footer">
      <br />
      <input type="hidden" id="idAnnonce" value="<?= $_POST['annonce'] ?>" />
      <input type="hidden" id="idCompteCriteres" value="<?= $iIdCompte ?>" />
      <div class="col-sm-offset-3 col-sm-6">
        <button type="button" class="btn btn-lg btn-primary col-xs-12" id="btn-criteres">
          <span class="col-xs-10 btn-text-left">Enregistrer</span>
          <span class="glyphicon glyphicon-play col-xs-2" aria-hidden="true"></span>
        </button>
        <br /><br />
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {

      $('.slider-label').each(function() {
        var crit = $(this).attr('crit');
        var pos = $(this).find('.pos').val();
        $('#phrase1-'+crit+', #phrase2-'+crit+', #phrase3-'+crit).hide();
        $('#phrase'+pos+'-'+crit).show();
        $("#slider"+crit).slider({
          range: "max",
          min: 1,
          max: 3,
          value: pos,
          slide: function(event, ui) {
            switch (ui.value) {
              case 1:
                $('#phrase2-'+crit+', #phrase3-'+crit).hide();
                $('#phrase1-'+crit).fadeIn('slow');
                $('#critere'+crit).val(1);
                break;
              case 2:
                $('#phrase1-'+crit+', #phrase3-'+crit).hide();
                $('#phrase2-'+crit).fadeIn('slow');
                $('#critere'+crit).val(2);
                break;
              case 3:
                $('#phrase1-'+crit+', #phrase2-'+crit).hide();
                $('#phrase3-'+crit).fadeIn('slow');
                $('#critere'+crit).val(3);
                break;
            }
          }
        });
      });

      $('#btn-criteres').click(function() {
        $.ajax({
          method: 'post',
          url: $('#ajax').val()+'ajax_criteres.php',
          data: 'critere1='+$('#critere1').val()+'&critere2='+$('#critere2').val()+'&critere3='+$('#critere3').val()+'&critere4='+$('#critere4').val()+'&critere5='+$('#critere5').val(),
          success: function() {
            $('#modaleCriteres').modal('hide');
            postuler($('#idAnnonce').val(), $('#idCompteCriteres').val());
          }
        });
      });

  });
</script>