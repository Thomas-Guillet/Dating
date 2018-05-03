<div class="col-sm-6">
	<br class="visible-xs" />
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
			
			<polygon points="100 25,100 100,137.5 35.05" class="polygon-horloge pointer" heure="0" etat="0" />
			<polygon points="137.5 35.05,100 100,164.95 62.50" class="polygon-horloge pointer" heure="1" etat="0" />
			<polygon points="164.95 62.50,100 100,175 100" class="polygon-horloge pointer" heure="2" etat="0" />
			<polygon points="175 100,100 100,164.95 137.5" class="polygon-horloge pointer" heure="3" etat="0" />
			<polygon points="164.95 137.5,100 100,137.5 164.95" class="polygon-horloge pointer" heure="4" etat="0" />
			<polygon points="137.5 164.95,100 100,100 175" class="polygon-horloge pointer" heure="5" etat="0" />
			<polygon points="100 175,100 100,62.5 164.95" class="polygon-horloge pointer" heure="6" etat="0" />
			<polygon points="62.5 164.95,100 100,35.05 137.5" class="polygon-horloge pointer" heure="7" etat="0" />
			<polygon points="35.05 137.5,100 100,25 100" class="polygon-horloge pointer" heure="8" etat="0" />
			<polygon points="25 100,100 100,35.05 62.5" class="polygon-horloge pointer" heure="9" etat="0" />
			<polygon points="35.05 62.5,100 100,62.5 35.05" class="polygon-horloge pointer" heure="10" etat="0" />
			<polygon points="62.5 35.05,100 100,100 25" class="polygon-horloge pointer" heure="11" etat="0" />
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
			
			<polygon points="100 25,100 100,137.5 35.05" class="polygon-horloge pointer" heure="12" etat="0" />
			<polygon points="137.5 35.05,100 100,164.95 62.50" class="polygon-horloge pointer" heure="13" etat="0" />
			<polygon points="164.95 62.50,100 100,175 100" class="polygon-horloge pointer" heure="14" etat="0" />
			<polygon points="175 100,100 100,164.95 137.5" class="polygon-horloge pointer" heure="15" etat="0" />
			<polygon points="164.95 137.5,100 100,137.5 164.95" class="polygon-horloge pointer" heure="16" etat="0" />
			<polygon points="137.5 164.95,100 100,100 175" class="polygon-horloge pointer" heure="17" etat="0" />
			<polygon points="100 175,100 100,62.5 164.95" class="polygon-horloge pointer" heure="18" etat="0" />
			<polygon points="62.5 164.95,100 100,35.05 137.5" class="polygon-horloge pointer" heure="19" etat="0" />
			<polygon points="35.05 137.5,100 100,25 100" class="polygon-horloge pointer" heure="20" etat="0" />
			<polygon points="25 100,100 100,35.05 62.5" class="polygon-horloge pointer" heure="21" etat="0" />
			<polygon points="35.05 62.5,100 100,62.5 35.05" class="polygon-horloge pointer" heure="22" etat="0" />
			<polygon points="62.5 35.05,100 100,100 25" class="polygon-horloge pointer" heure="23" etat="0" />
		</svg>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		var jour = $('#jour').val();
		var heures = $('#heures'+jour).val();
		var aHeures = heures.split('');
		for (i=0; i<24; i++) {
			if (aHeures[i] == '1') {
				$('.polygon-horloge[heure="'+i+'"]').attr('etat', '1');
				$('.polygon-horloge[heure="'+i+'"]').css({
					'fill': '#007CFF',
					'stroke': 'white'
				});
			}
		}

		$('.polygon-horloge').click(function() {
			var jour = $('#jour').val();
			var heure = $(this).attr('heure');
			if ($(this).attr('etat') == 0) {
				$(this).css({
					'fill': '#007CFF',
					'stroke': 'white'
				});
				$(this).attr('etat', 1);
				var sOldHeures = $('#heures'+jour).val();
				var sNewHeures = sOldHeures.substr(0, heure)+'1'+sOldHeures.substr(parseInt(heure)+1);
			} else {
				$(this).css({
					'fill': 'transparent',
					'stroke': 'transparent'
				});
				$(this).attr('etat', 0);
				var sOldHeures = $('#heures'+jour).val();
				var sNewHeures = sOldHeures.substr(0, heure)+'0'+sOldHeures.substr(parseInt(heure)+1);
			}
			$('#heures'+jour).val(sNewHeures);
		});

	});
</script>