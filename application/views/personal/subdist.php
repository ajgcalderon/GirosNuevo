<?php foreach ($subdistribuidores as $subdistribuidor) { ?>
            <option value="<?php echo $subdistribuidor->subgrupo;?>"><?php echo $subdistribuidor->nombre . ' ' . $subdistribuidor->apellido . ' (' .  $subdistribuidor->subgrupo . ')';?></option>
        <?php }?>