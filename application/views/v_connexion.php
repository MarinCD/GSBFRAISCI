<?php 
    echo '<div id="sousContenu">';
        echo form_open(site_url('Connexion/valider_connexion')); 
        echo '<div class="corpsForm">';
            echo '<p>';
                echo form_label('Login*', 'txtLogin');  
                $data = [	'name'      => 'txtLogin',
                            'id'        => 'txtLogin',
                            'maxlength'	=> '45',
                            'size'      => '15'
                        ];
                echo form_input($data);   
            echo '</p>';
            echo '<p>';
                echo form_label('Mot de passe*', 'pwdMdp');  
                $data = [	'type'      => 'password',
                            'name'      => 'pwdMdp',
                            'id'        => 'pwdMdp',
                            'maxlength'	=> '45',
                            'size'      => '15'
                        ];
                echo form_input($data);   
            echo '</p>';
        echo '</div>';
        echo '<div class="piedForm">';
            echo '<p>';
                $data = [	'type' 	=> 'submit', 
                            'class'	=> 'bouton',
                            'value' => 'Valider',
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
    echo '</div>'; 
?>