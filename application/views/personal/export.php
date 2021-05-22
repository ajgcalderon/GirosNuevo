<?php 
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Type: application/vnd.ms-excel; ");
header("Content-Disposition: attachment;filename=Reporte de Comisiones.xls");
?>
<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Total Recargas</th>
            <th>Comisiones</th>
            <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?><th>Grupo</th><?php }?>
            <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR){?><th>Subgrupo</th><?php }?>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach($cuentas as $cuenta){
        ?>
            <tr>
                <td><?php if($desde==$hasta){echo $desde;}else{echo $desde . ' - ' . $hasta;}?></td>
                <td><?php echo $cuenta['nombre'] ?></td>
                <td><?php echo '$ ' . number_format($cuenta['pesos'],2,',','.');?></td>
                <td><?php echo '$ ' . number_format($cuenta['comision'],2,',','.');?></td>
                <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?><td><?php echo $cuenta['grupo'];?></td><?php }?>
                <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR){?><td><?php if($cuenta['subgrupo']){?><?php echo $cuenta['subgrupo'];?><?php }?></td><?php }?>
            </tr>
            
        <?php  }?>
            
    </tbody>
</table>