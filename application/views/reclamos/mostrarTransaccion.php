<!-- <div class="alert alert-default">
    <!-?php echo $transaccion->fecha . ' ' . $transaccion->referencia . ' ' . $transaccion->cedula . ' ' . $transaccion->beneficiario . ' ' . $transaccion->banco . ' ' . $transaccion->cuenta . ' ' . $transaccion->monto?>
</div> -->
<?php if($transaccion){
        if($transaccion->estado == ESTADO_TRANSACCION_VERIFICACION){ ?>
        <div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><center>Su transacción se encuenta en <strong>Espera de Correción</strong>, Por favor dirijirse a la Tienda para verificar los datos del Beneficiario</center></div>
<?php
        }
    ?>
    
<div class="alert alert-info alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <div class="table-responsive">
        
        <table class="table">
            <thead>
                <tr>
                    <th>Referenica</th>
                    <th>Fecha</th>
                    <th>Cedula</th>
                    <th>Beneficiario</th>
                    <th>Banco</th>
                    <th>Cuenta</th>
                    <th>Monto</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $transaccion->referencia ;?></td>
                    <td><?php echo $transaccion->fecha ;?></td>
                    <td><?php echo $transaccion->cedula ;?></td>
                    <td><?php echo $transaccion->beneficiario ;?></td>
                    <td><?php echo $transaccion->banco ;?></td>
                    <td><?php echo $transaccion->cuenta ;?></td>
                    <td><?php echo number_format($transaccion->monto,2,',','.');?></td>
                    <td><?php echo $transaccion->estado ;?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!--?php echo $transaccion->fecha . ' ' . $transaccion->referencia . ' ' . $transaccion->cedula . ' ' . $transaccion->beneficiario . ' ' . $transaccion->banco . ' ' . $transaccion->cuenta . ' ' . $transaccion->monto?-->
</div>
<?php }
if (isset($transferencia)){?>
    <a href="<?php echo $transferencia->captura;?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo $transferencia->captura;?>" alt="Captura" class="col-lg-12"></a>
<?php }
?>
