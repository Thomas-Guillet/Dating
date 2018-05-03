<?php

session_start();

include_once '../../../config/constantes.php';

//On récupère l'id compte
$iIdCompte = $_SESSION[SESSION]['compte'];

?>

<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h3 class="modal-title">Attention</h3>
    </div>
    <div class="modal-body text-center grisFonce">
      <p class="lead">
        Il est préférable de posséder le permis<br />
        pour postuler à ce job.
      </p>
      <label class="radio-inline">
        <input type="radio" name="permis" value="oui" />
        J'ai le permis
      </label>
      <label class="radio-inline">
        <input type="radio" name="permis" value="non" checked="checked" />
        Je n'ai pas le permis
      </label><br /><br />
    </div>
    <div class="modal-footer">
      <p class="lead">Souhaites-tu toujours postuler ?</p>
      <input type="hidden" id="idAnnonce" value="<?= $_POST['annonce'] ?>" />
      <input type="hidden" id="idComptePermis" value="<?= $iIdCompte ?>" />
      <div class="row">
        <div class="col-sm-offset-1 col-sm-5">
          <button type="button" class="btn btn-lg btn-primary col-xs-12" id="btn-permis">
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
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {

    $('#btn-permis').click(function() {
      $.ajax({
        method: 'post',
        url: $('#ajax').val()+'ajax_permis.php',
        data: 'permis='+$('[name=permis]:checked').val(),
        success: function() {
          $('#modalePermis').modal('hide');
          postuler($('#idAnnonce').val(), $('#idComptePermis').val());
        }
      });
    });

  });
</script>