<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Transferencias</h1>
    </div>
    <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_TIENDA  || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR){?>
    <div class="col-lg-2 pull-right row">
        <a href="Transacciones/factura?transaccion=<?php echo $transaccion->referencia;?>" target="_blank" class="btn btn-default btn-lg">
            <span class="fa fafw fa-print"></span>
        </a>
    </div>
    <?php }?>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                Información Completa de la Transferencia
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                    <center><h2 class="text-dark page-header">Transaccion</h2></center>
                    <div class="col-12 row">
                        
                        <div class="col-lg-offset-3 col-lg-6 row">
                            <div class="col-lg-6">
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
                                    <strong>Telefono de Contacto</strong><br><br>
                                    <strong>Motivo</strong>
                                <!-- <strong>Tienda</strong><br>
                                <strong>Girador</strong><br> -->
                            </div>
                            <div class="col-lg-6">
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
                                    <?php echo $transaccion->telefono;?><br><br>

                                    <?php echo $transaccion->motivo;?><br>
                            </div>
                        </div>       
                    </div>
                    <center><h2 class="text-dark page-header">Transferencia</h2></center>
                <?php 
                    switch ($this->session->userdata('tipo')) {
                        case USUARIO_ADMINISTRADOR:
                    ?>
                        
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Nº Referencia</th>
                                    <th>Girador</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd">
                                    <td><?php echo $transferencia->referencia;?></td>
                                    <td><?php echo $transferencia->nombreGirador . ' ' . $transferencia->apellidoGirador;?></td>
                                    <td><?php echo $transferencia->fecha;?></td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- /.table-responsive -->
                <?php        
                        break;
                    case USUARIO_SUPERVISOR:
                ?>
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Nº Referencia</th>
                                    <th>Girador</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $transferencia->referencia;?></td>
                                    <td><?php echo $transferencia->nombreGirador . ' ' . $transferencia->apellidoGirador;?></td>
                                    <td><?php echo $transferencia->fecha;?></td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- /.table-responsive -->
                <?php
                        break;
                }
            ?>
            <a href="<?php echo $transferencia->captura;?>"  target="_blank" ><img src="<?php echo $transferencia->captura;?>" width="100%" alt="captureTransferencia"></a>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="col-lg-12">
    <a href="Transferencias?&tienda=<?php echo $tienda; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta?>" class="btn btn-primary pull-right btn-outline">Volver</i></a>
</div>