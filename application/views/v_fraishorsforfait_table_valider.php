    <h4>Descriptif des éléments hors forfait</h4>
    <table class="listeLegere">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class="montant">Montant</th>     
            <th class="action">Refus</th>
            <th class="action">Report</th>                      
        </tr>
<?php   foreach($fhf as $unfhf) : 
            $date = $this->gsb_lib->date_vers_francais($unfhf['date']);
            $libelle = $unfhf['libelleFraisHorsForfait'];
            $montant = $this->gsb_lib->format_montant($unfhf['montant']); 
            $id = $unfhf['idFraisHorsForfait'] ;
            $refus = substr($unfhf['libelleFraisHorsForfait'], 0,8) == "REFUSE: ";
            ?>
           
            <tr>
                <td><?php echo $date ?></td>
                <td class="libelle"><?php echo $libelle ?></td>
                <td class="montant"><?php echo $montant ?></td>

                
                <td>
                <?php if(!$refus): ?>
                    <a 	href="<?php echo  site_url('validerfrais/refuser_fhf/'.$id.'/'.$lst_select) ?>" 
                        onclick="return confirm('Voulez-vous vraiment refuser ce frais ?');">
                        <img src="<?php echo  site_url('../assets/images/delete.png') ?> " />
                    </a>
                    <?php endif ?>

                </td>
                <td>
                <?php if(!$refus): ?>
                    <a 	href="<?php echo  site_url('validerfrais/reporter_fhf/'.$id.'/'.$lst_select) ?>" 
                        onclick="return confirm('Voulez-vous vraiment reporter ce frais ?');">
                        <img src="<?php echo  site_url('../assets/images/redo.png') ?> " />
                    </a>
                    <?php endif ?>
                </td>
            </tr>
<?php   endforeach ?>
    </table>
    &nbsp;