    <h4>Eléments forfaitisés :</h4>
	<table class="listeLegere">
		<tr>
        <?php foreach ($ff as $unff): ?>
			    <th><?php echo $unff['libelleFraisForfait'] ?></th>
        <?php endforeach; ?>
		</tr>
		<tr>
        <?php foreach ($ff as $unff): ?>
                <td class="qteForfait"> <?php echo $unff['quantite'] ?> </td>
        <?php endforeach; ?>
		</tr>
	</table>