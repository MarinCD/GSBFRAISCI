    <h4>Descriptif des éléments hors forfait</h4>
    <table class="listeLegere">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class="montant">Montant</th>     
            <th class="action">&nbsp;</th>           
        </tr>
<?php   foreach($fhf as $unfhf) : 
            $date = $this->gsb_lib->date_vers_francais($unfhf['date']);
            $libelle = $unfhf['libelleFraisHorsForfait'];
            $montant = $this->gsb_lib->format_montant($unfhf['montant']); 
            $id = $unfhf['idFraisHorsForfait'] ?>
            <tr>
                <td><?php echo $date ?></td>
                <td class="libelle"><?php echo $libelle ?></td>
                <td class="montant"><?php echo $montant ?></td>
                <td>
                    
                    <a 	href="<?php echo  site_url('gererfrais/supprimer_fraishorsforfait/'.$id) ?>" 
                        onclick="return confirm('Voulez-vous vraiment supprimer ce frais ?');">
                        <img src="<?php echo  site_url('../assets/images/delete.png') ?> " />
                    </a>
                </td>
            </tr>
<?php   endforeach ?>
    </table>
    &nbsp;