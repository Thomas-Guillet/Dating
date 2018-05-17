<!DOCTYPE html>
<?php

include_once '../config/constantes.php';
include_once '../controllers/app.php';
include_once 'reusable/header.php';

?>
<div id="view">
	<div id="home" class="active">
		<!-- <span class="logo fontSize110"></span> -->
		<img src="<?= IMG ?>logo.png" />
		<h1>TELL ME IS JOB AND I WILL TELL YOU WHAT HE WANTS</h1>
		<div class="search__form">
			<select>
				<option>
					Lawyer
				</option>
			</select>
			<button id="btn__search">OK</button>
		</div>
		<br />
		<span class="description">
			Take art in this experience by choosing the job of your partner (or futur partner) <br />
			in those proposed above. We have tried to best represent the data collected. <br />
			The figures shown are the result of an experiment. For mor details, <span id="btn__learn__more">click here.</span>
		</span>
	</div>

	<div id="learn__more">
		<div id="arrow__back"><img src="<?= IMG ?>arrow.png" /></div>
		<img src="<?= IMG ?>logo.png" />
		<div class="title">LEARN MORE</div>
		<br />
		<br />
		<span class="description">
			Take part in this experience by choosing the job of your partner (or future partner)<br />
			in those proposed above. We have tried to best represent the data collected.<br />
			The figures shown are the result of an experiment.<br />
			<br />
			<br />
			This dataset was compiled by Columbia Business School professors. Data were collected from<br />
			participants in rapid dating experimental events from 2002 to 2004.<br />
			During the events, participants would have a "first date" of four minutes with all other participants of<br />
			the opposite sex. The data used comes from: <a href="https://www.kaggle.com/annavictoria/speed-dating-experiment" target="_blank">https://www.kaggle.com/annavictoria/speed-dating-experiment</a>
		</span>
	</div>

	<div id="data__container">
		<span class="logo floatLeft	fontSize70"></span>
		<div id="arrow__back"><img src="<?= IMG ?>arrow.png" /></div>
		<?php include 'reusable/panel.php'; ?>
		<div class="diagram goal"></div>
		<div class="diagram self__confidence"></div>
		<?php include 'reusable/loader.php'; ?>
	</div>
</div>


<?php

include_once 'reusable/footer.php';

?>
