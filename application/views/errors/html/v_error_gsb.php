<div class="erreur">
    <ul>
<?php foreach($_REQUEST['erreurs'] as $erreur) : ?>
        <li>
        <?php echo $erreur; ?>
        </li>
<?php endforeach; ?>
    </ul>
</div>