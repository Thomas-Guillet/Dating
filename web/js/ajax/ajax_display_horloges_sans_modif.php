<?php 

$aHeures = $_POST['heures'];

?>

<div class="col-sm-6">
	<p class="text-center fontSize20 bleuClair"><b>Matin</b></p>
	<div class="div-horloge">
		<svg height="200" width="200" class="svg-horloge">
			<circle cx="100" cy="100" r="75" stroke="#007CFF" stroke-width="1" fill="transparent" />

			<line x1="100" y1="175" x2="100" y2="25" stroke="#007CFF" fill="transparent" stroke-width="1"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1" transform="rotate(30, 100, 100)"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1" transform="rotate(60, 100, 100)"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1" transform="rotate(120, 100, 100)"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1" transform="rotate(150, 100, 100)"/>
			
			<polygon points="100 25,100 100,137.5 35.05" class="polygon-horloge pointer" heure="0" />
			<polygon points="137.5 35.05,100 100,164.95 62.50" class="polygon-horloge pointer" heure="1" />
			<polygon points="164.95 62.50,100 100,175 100" class="polygon-horloge pointer" heure="2" />
			<polygon points="175 100,100 100,164.95 137.5" class="polygon-horloge pointer" heure="3" />
			<polygon points="164.95 137.5,100 100,137.5 164.95" class="polygon-horloge pointer" heure="4" />
			<polygon points="137.5 164.95,100 100,100 175" class="polygon-horloge pointer" heure="5" />
			<polygon points="100 175,100 100,62.5 164.95" class="polygon-horloge pointer" heure="6" />
			<polygon points="62.5 164.95,100 100,35.05 137.5" class="polygon-horloge pointer" heure="7" />
			<polygon points="35.05 137.5,100 100,25 100" class="polygon-horloge pointer" heure="8" />
			<polygon points="25 100,100 100,35.05 62.5" class="polygon-horloge pointer" heure="9" />
			<polygon points="35.05 62.5,100 100,62.5 35.05" class="polygon-horloge pointer" heure="10" />
			<polygon points="62.5 35.05,100 100,100 25" class="polygon-horloge pointer" heure="11" />
		</svg>
	</div>
</div>
<div class="visible-xs"><br /><br /></div>
<div class="col-sm-6">
	<p class="text-center fontSize20 bleuClair"><b>Après-midi</b></p>
	<div class="div-horloge">
		<svg height="200" width="200" class="svg-horloge">
			<circle cx="100" cy="100" r="75" stroke="#007CFF" stroke-width="1" fill="transparent" />

			<line x1="100" y1="175" x2="100" y2="25" stroke="#007CFF" fill="transparent" stroke-width="1"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1" transform="rotate(30, 100, 100)"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1" transform="rotate(60, 100, 100)"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1" transform="rotate(120, 100, 100)"/>
			<line x1="25" y1="100" x2="175" y2="100" stroke="#007CFF" fill="transparent" stroke-width="1" transform="rotate(150, 100, 100)"/>
			
			<polygon points="100 25,100 100,137.5 35.05" class="polygon-horloge pointer" heure="12" />
			<polygon points="137.5 35.05,100 100,164.95 62.50" class="polygon-horloge pointer" heure="13" />
			<polygon points="164.95 62.50,100 100,175 100" class="polygon-horloge pointer" heure="14" />
			<polygon points="175 100,100 100,164.95 137.5" class="polygon-horloge pointer" heure="15" />
			<polygon points="164.95 137.5,100 100,137.5 164.95" class="polygon-horloge pointer" heure="16" />
			<polygon points="137.5 164.95,100 100,100 175" class="polygon-horloge pointer" heure="17" />
			<polygon points="100 175,100 100,62.5 164.95" class="polygon-horloge pointer" heure="18" />
			<polygon points="62.5 164.95,100 100,35.05 137.5" class="polygon-horloge pointer" heure="19" />
			<polygon points="35.05 137.5,100 100,25 100" class="polygon-horloge pointer" heure="20" />
			<polygon points="25 100,100 100,35.05 62.5" class="polygon-horloge pointer" heure="21" />
			<polygon points="35.05 62.5,100 100,62.5 35.05" class="polygon-horloge pointer" heure="22" />
			<polygon points="62.5 35.05,100 100,100 25" class="polygon-horloge pointer" heure="23" />
		</svg>
	</div><br /><br />
</div>
<input type="hidden" id="heures" value="<?= $aHeures ?>" />

<script type="text/javascript">
	$(document).ready(function() {

		var heures = $('#heures').val();
		var aHeures = heures.split('');
		for (i=0; i<24; i++) {
			if (aHeures[i] == '1') {
				$('.polygon-horloge[heure="'+i+'"]').css({
					'fill': '#007CFF',
					'stroke': 'white'
				});
			}
		}

	});
</script>