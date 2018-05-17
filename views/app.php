<?php

include_once '../config/constantes.php';
include_once CONTROLLERS.'app.php';
include_once HEADER;
echo CONTROLLERS.'app.php';
?>
<div id="view">
	<div id="home" class="active">
		<img src="<?= IMG ?>logo.png" />
		<h1>DIT MOI SON MÉTIER JE TE DIRAIS CE QU'IL CHERCHE</h1>
		<select>
			<option>
				Lawyer
			</option>
		</select>
		<button id="btn__search">OK</button>
		<br />
		<span>
			Participez à cette expérience en choisussant le métier de votre partenaire (ou futur partenaire)<br />
			dans ceux proposés ci-dessu. Nous avons essayé de représenter au mieux les données récoltées.<br />
			Les chiffres exposés sont le résultat d'une expérience. Pour plus de détails, <a>cliquez ici</a>.
		</span>
	</div>

	<div id="data__container">
		<span class="logo"></span>
		<?php include REUSABLES.'panel.php'; ?>
		<div class="diagram goal">

		</div>
		<div class="diagram self__confidence">

		</div>
	</div>
</div>


<?php

include_once FOOTER;

?>
