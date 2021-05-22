<?php 
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Type: application/vnd.ms-excel; ");
header("Content-Disposition: attachment;filename=Reporte de Recargas.xls");
?>
<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Referencia</th>
                <th>Tienda</th>
                <th>Monto $COP</th>
                <th>Comision</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                
            foreach($recargas as $recarga){
                if($recarga->monto > 0) {
            ?>
                <tr>
                    <td><?php echo $recarga->fecha;?></td>
                    <td><?php echo $recarga->referencia;?></td>
                    <td><?php echo $recarga->nombre . ' ' . $recarga->apellido;?></td>
                    <td><?php echo '$ ' . number_format($recarga->monto,2,',','.');?></td>
                    <td><?php echo '$ ' . number_format($recarga->monto*$recarga->comision/100,2,',','.');?></td>
                    <td><?php echo '$ ' . number_format($recarga->monto+$recarga->monto*$recarga->comision/100,2,',','.');?></td>
                    <td><?php echo $recarga->estado;?></td>
                </tr>
                
            <?php }}?>
        </tbody>
</table>