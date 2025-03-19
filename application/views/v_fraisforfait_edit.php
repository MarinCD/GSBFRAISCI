<?php 
	//echo form_open(site_url('Gererfrais/valider_maj_fraisforfait'));
    echo heading($heading, 4);
    echo form_open(site_url($action)); 
    echo '<div class="corpsForm">';
        foreach ($ff as $unff):
            echo '<p>';
            echo form_label($unff['libelleFraisForfait'].' : ', 'nb'.$unff['idFraisForfait']);  
            $data = [	'type' 		=> 'number',
                        'name'      => 'lesFrais['.$unff['idFraisForfait'].']',
                        'id'        => 'nb'.$unff['idFraisForfait'],
                        'maxlength'	=> '4',
                        'size'      => '4',
                        'min'       => '0',
                        'max'       => '1000',
                        'value'     => $unff['quantite'],
                        'style'     => 'text-align : right'
                    ];
            echo form_input($data);   
            echo '</p>';
        endforeach;    
    echo '</div>';
    echo '<div class="piedForm">';
        echo '<p>';
            $data = [	'type' 	=> 'submit', 
                        'class'	=> 'bouton',
                        //'value' => 'Valider',
                        'value' => $label,
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