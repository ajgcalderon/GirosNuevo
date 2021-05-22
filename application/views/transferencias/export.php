<?php 
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Type: application/vnd.ms-excel; ");
header("Content-Disposition: attachment;filename=Reporte de Transferencias.xls");
?>
<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Nº Referencia</th>
            <?php if($this->session->userdata('tipo')!= USUARIO_GIRADOR){?><th>Girador</th><?php }?>
            <th>Beneficiario</th>
            <?php if($this->session->userdata('tipo')!= USUARIO_SUPERVISOR && $this->session->userdata('tipo')!= USUARIO_GIRADOR){?><th>Pesos</th><?php }?>
            <?php if($this->session->userdata('tipo')!= USUARIO_SUPERVISOR && $this->session->userdata('tipo')!= USUARIO_GIRADOR){?><th>Tasa</th><?php }?>
            <th>Monto</th>
            <th>Transaccion</th>
            <th>Tienda</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php
            
            foreach($transferencias as $transferencia){
                
        ?>
                    <tr>
                        <td><?php echo $transferencia->fecha;?></td>
                        <td><?php echo $transferencia->referencia;?></td>
                        <?php if($this->session->userdata('tipo')!= USUARIO_GIRADOR){?><td><?php echo $transferencia->girador;?></td><?php }?>
                        <td><?php echo $transferencia->beneficiario;?></td>
                        <?php if($this->session->userdata('tipo')!= USUARIO_SUPERVISOR && $this->session->userdata('tipo')!= USUARIO_GIRADOR){?><td><?php echo '$ ' . number_format($transferencia->pesos,2,',','.');?></td><?php }?>
                        <?php if($this->session->userdata('tipo')!= USUARIO_SUPERVISOR && $this->session->userdata('tipo')!= USUARIO_GIRADOR){?><td><?php echo $transferencia->tasa;?></td><?php }?>
                        <td><?php echo 'Bs.S ' . number_format($transferencia->monto,2,',','.');?></td>
                        <td><?php echo $transferencia->transaccion;?></td>
                        <td><?php echo $transferencia->nombreTienda;?></td>
                        <td><?php if($transferencia->estado == ESTADO_TRANFERENCIAS_EJECUTADA){echo "Procesada";}else{echo "Devuelta";};?></td>
                    </tr>
        <?php
            }
        ?>
    </tbody>
</table>