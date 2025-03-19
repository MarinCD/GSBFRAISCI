    <h4>Descriptif des éléments hors forfait -<?php echo $fiche['nbJustificatifs'] ?> justificatifs reçus -</h4>
	<table class="listeLegere">
		<tr>
			<th class="date">Date</th>
			<th class="libelle">Libellé</th>
            <th class="montant">Montant</th>                
        </tr>
<?php   foreach ($fhf as $unfhf): 
            $date = $this->gsb_lib->date_vers_francais($unfhf['date']);
            $libelle = $unfhf['libelleFraisHorsForfait'];
            $montant = $this->gsb_lib->format_montant($unfhf['montant']); ?>  
            <tr>
                <td><?php echo $date ?></td>
                <td class="libelle"><?php echo $libelle ?></td>
                <td class="montant"><?php echo $montant ?></td>
            </tr>
<?php   endforeach; ?>
	</table>
	&nbsp;