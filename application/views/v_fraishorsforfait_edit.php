<?php
	echo heading($heading, 4);
	echo form_open(site_url('Gererfrais/valider_creation_fraishorsforfait')); 
	echo '<div class="corpsForm">';
		echo '<p>';
			echo form_label('Date (jj/mm/aaaa) : ', 'txtDateHF');
			$data = [	'type' 		=> 'date',
						'name'      => 'txtDateHF',
						'id'        => 'txtDateHF',
						'maxlength'	=> '10',
						'size'      => '10'
					];
			echo form_input($data);
		echo '</p>';
		echo '<p>';
			echo form_label('Libellé : ', 'txtLibelleHF');
			$data = [	'name'      => 'txtLibelleHF',
						'id'        => 'txtLibelleHF',
						'maxlength'	=> '256',
						'size'      => '50'
					];
			echo form_input($data);
		echo '</p>';
		echo '<p>';
			echo form_label('Montant : ', 'txtMontantHF');
			$data = [	'name'      => 'txtMontantHF',
						'id'        => 'txtMontantHF',
						'maxlength'	=> '12',
						'size'      => '12'
					];
			echo form_input($data);
		echo '</p>';
	echo '</div>';
	echo '<div class="piedForm">';
		echo '<p>';
			$data = [	'type' 	=> 'submit', 
						'class'	=> 'bouton',
						'value' => 'Ajouter',
						'size'  => '20' 
					];
			echo form_input($data);
			$data = [	'type' 	=> 'reset',
						'class'	=> 'bouton',
						'value' => 'Effacer',
						'size'  => '20'
					];
			echo form_input($data);
		echo '</p>';
	echo '</div>';
	echo form_close();
?>	
