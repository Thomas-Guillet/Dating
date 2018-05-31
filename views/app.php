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
			<input type="hidden" id="career-selected" />
			<select id="career">
				<?php foreach ($aCareer as $key => $sCareer) { ?>
					<option value="<?= $sCareer ?>">
						<?= $sCareer ?>
					</option>
				<?php } ?>
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
		<div id="diagram__goal" class="diagram goal">
			<div id="label__pie__chart" class="label">
				<span>Lawyer</span>
			</div>
			<div class="label__circle">
				<div id="pie-label-1">
					<div class="perc">40 %</div>
					<div class="gender__perc">40 %</div>
					<div class="label">seem like a fun night out</div>
				</div>
			</div>

			<div class="label__circle rotate60">
				<div id="pie-label-2">
					<div class="perc">40 %</div>
					<div class="gender__perc">40 %</div>
					<div class="label">To meet new people</div>
				</div>
			</div>

			<div class="label__circle rotate120">
				<div id="pie-label-3">
					<div class="perc">40 %</div>
					<div class="gender__perc">40 %</div>
					<div class="label">To say « i did it »</div>
				</div>
			</div>

			<div class="label__circle rotate180">
				<div id="pie-label-4">
					<div class="perc">40 %</div>
					<div class="gender__perc">40 %</div>
					<div class="label">To get a date</div>
				</div>
			</div>

			<div class="label__circle rotate240">
				<div id="pie-label-5">
					<div class="perc">40 %</div>
					<div class="gender__perc">40 %</div>
					<div class="label">Looking for a serious relationship</div>
				</div>
			</div>

			<div class="label__circle rotate300">
				<div id="pie-label-6">
					<div class="perc">40 %</div>
					<div class="gender__perc">40 %</div>
					<div class="label">Other</div>
				</div>
			</div>
			<canvas id="myCanvas" width="600" height="600" ></canvas>
		</div>
		<div id="text__self__confidence" class="text__self__confidence">
			Before the speed-dating, we asked<br />
			50 people to answer this question :<br /><br />
			Out of the 20 people you will meet,<br />
			how many do you expect will be<br />
			interestedin dating you ?
		</div>
		<div id="label__self__confidence" class="label__self__confidence">
			<span>Lawyer</span>
		</div>
		<div id="diagram__self__confidence" class="diagram self__confidence">

			<div id="data_render" class="hide">
				<div id="column-1"></div>
				<div id="column-2"></div>
				<div id="column-3"></div>
				<div id="column-4"></div>
				<div id="column-5"></div>
				<div id="column-6"></div>
				<div id="column-7"></div>
				<div id="column-8"></div>
				<div id="column-9"></div>
				<div id="column-10"></div>
				<div id="column-11"></div>
				<div id="column-12"></div>
				<div id="column-13"></div>
				<div id="column-14"></div>
				<div id="column-15"></div>
				<div id="column-16"></div>
				<div id="column-17"></div>
				<div id="column-18"></div>
				<div id="column-19"></div>
				<div id="column-20"></div>
			</div>
			<div class="array">
				<div class="horizontal__label">
					<span class="left">0</span>
					<span class="middle">10</span>
					<span class="right">20</span>
				</div>
				<div class="vertical__label">
					<span class="one">5</span>
					<span class="two">10</span>
					<span class="three">15</span>
					<span class="four">20</span>
					<span class="five">25</span>
					<span class="six">30</span>
				</div>
				<div class="line one"></div>
				<div class="line two"></div>
				<div class="line three"></div>
				<div class="line four"></div>
				<div class="line five"></div>
				<div class="line six"></div>
				<div class="line seven"></div>
			</div>
		</div>
	</div>
</div>

<?php include 'reusable/loader.php'; ?>

<?php

include_once 'reusable/footer.php';

?>
