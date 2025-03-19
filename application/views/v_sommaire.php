    <div id="menuGauche">
		<div id="infosUtil">
			<div id="user">
				<img src="<?php echo site_url('../assets/images/UserIcon.png') ?>" />
			</div>
			<div id="infos">
                <h2><?php echo $this->session->prenom." ".$this->session->nom  ?> </h2>
				<h3> <?php echo $this->session->libelleRole ?> </h3>  
			</div>
			<ul class="menuList">
				<li class="smenu">
                    <?php echo anchor('Connexion/deconnexion', 'Déconnexion', 'title="Se déconnecter"'); ?>
				</li>
			</ul>    
		</div>  
        <ul id="menuPrincipal" class="menuList">
				
				<li class="smenu">
                	<?php echo anchor('Accueil', 'Accueil', 'title="Accueil"'); ?>
				</li>

			<?php	$menu = $this->gsb_lib->get_elts_menu($this->session->idRole); 
			foreach($menu as $element ) :
			?>
			
				<li class="smenu">
                	<?php echo anchor($element['page'], $element['libelle'], 'title="'.$element['titre'].'"'); ?>
				</li>
			<?php endforeach ?>
			

    </div>