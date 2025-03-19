<div id="etatMontant">
    <div id="etat" class="<?php echo $fiche['idEtat'];?>">
        <?php echo $fiche['libelleEtat']?> depuis le <?php echo $this->gsb_lib->date_vers_francais($fiche['dateModif']) ?> 
    </div>
    <div id="montantValide">
    <?php echo $fiche['libelle']?>
        <div><?php echo $this->gsb_lib->format_montant($fiche['montantValide']) ?> &#8364;</div>
    </div>
</div>	