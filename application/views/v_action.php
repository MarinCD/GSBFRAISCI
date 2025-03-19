<?php
	echo form_open(site_url($action)); 
	echo '<div class="piedFormAction">';
		echo '<p>';
		$data = [	'type' 	=> 'hidden', 
		'name' => 'idFiche',
		'value' => $lst_select,
		'size'  => '60' 
	];
echo form_input($data);


			$data = [	'type' 	=> 'submit', 
						'class'	=> 'ActionButton',
						'value' => $boutonLabel
					];
			echo form_input($data);
		echo '</p>';
	echo '</div>';
	echo form_close();
?>	
