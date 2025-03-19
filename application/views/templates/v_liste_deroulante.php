<div class="corpsForm"> 
<?php 
    echo form_open(site_url($lst_action));  
    echo form_label($lst_label." : ", $lst_id); 
    echo form_dropdown($lst_id, $lst_contenu, $lst_select, 'onChange="this.form.submit()"');
    echo form_close();
?>
</div>
