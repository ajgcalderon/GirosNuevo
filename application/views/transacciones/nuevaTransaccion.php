<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Transacciones</h1>
    </div>
    
    <div class="col-lg-12">&nbsp;</div>
    <!-- /.col-lg-12 -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="panel panel-yellow">
                <div class="panel-heading"> 
                    Transaccion
                </div>
                <div class="panel-body">
                    <div class="row">                       
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6 col-lg-offset-3 col-md-12 col-sm-12 col-12">
                            <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                <strong>Fecha</strong><br>
                                <strong>N° Referencia</strong><br>
                                <strong>Monto</strong><br>
                                <strong>Tasa</strong><br>
                                <strong>Pesos</strong><br>
                                <strong>Estado</strong><br><br>
                                
                                <strong>Beneficiario</strong><br>
                                <strong>Cedula</strong><br>
                                <strong>Banco</strong><br>
                                <strong>Numero de Cuenta</strong><br><br>
                                
                                <strong>Nombre del Cliente</strong><br>
                                <strong>Telefono de Contacto</strong><br>
                            </div>
                            <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                <?php echo $transaccion->fecha;?><br>
                                <?php echo $transaccion->referencia;?><br>
                                <?php echo 'Bs.S ' . number_format($transaccion->monto,2,',','.');?><br>
                                <?php echo $transaccion->tasa;?><br>
                                <?php echo '$COP ' . number_format($transaccion->pesos,2,',','.');?><br>
                                <?php echo $transaccion->estado;?><br><br>
                                
                                <?php echo $transaccion->beneficiario;?><br>
                                <?php echo $transaccion->cedula;?><br>
                                <?php echo $transaccion->banco;?><br>
                                <?php echo substr($transaccion->cuenta,0,4) . '-' . substr($transaccion->cuenta,4,4) . '-' . substr($transaccion->cuenta,8,4) . '-' . substr($transaccion->cuenta,12,4) . '-' . substr($transaccion->cuenta,16,4);?><br><br>

                                <?php echo $transaccion->nombreCliente;?><br>
                                <?php echo $transaccion->telefono;?><br>
                            </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div> 
    <div class="col-lg-4 pull-right row">
        
        <?php 
            $mensaje = "Hola has hecho un giro a Venezuela por *$ " . number_format($transaccion->pesos,2,',','.') ."* el id de transacción es *" . $transaccion->referencia . "* a continuación puedes ver el soporte de pago en el siguiente link " . base_url();      
        ?>
        <a href="http://api.whatsapp.com/send?phone=<?php echo $transaccion->telefono;?>&text=<?php echo $mensaje;?>" data-text="BOTÓN COMPARTIR EN WHATSAPP" data-action="share/whatsapp/share" class="miestilo col-lg-6 btn btn-success btn-lg">
            <i class="fa fafw fa-whatsapp"></i>
        </a>
        <a href="Transacciones/factura?transaccion=<?php echo $transaccion->referencia;?>" target="_blank" class="btn btn-default btn-lg col-lg-6">
            <span class="fa fafw fa-print"></span>
        </a>
    </div>  
</div>