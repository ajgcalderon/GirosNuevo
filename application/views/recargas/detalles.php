<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Recarga</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Información Completa de la Recarga
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- <h2 class="text-dark">Transaccion</h2> -->
                <?php if(isset($error)){ ?>
                    <div class="alert alert-danger col-lg-4 col-lg-offset-4"><center><?php echo $error;?></center></div>
                <?php }?>
                <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6 row">
                            <div class="col-lg-6">
                                <strong>Fecha</strong><br>
                                <strong>N° Referencia</strong><br>
                                <strong>Monto</strong><br>
                                <strong>Tienda</strong><br>
                                
                                <!-- <strong>Tienda</strong><br>
                                <strong>Girador</strong><br> -->
                            </div>
                            <div class="col-lg-6">
                                <?php echo $recarga->fecha;?><br>
                                <?php echo $recarga->referencia;?><br>
                                <?php echo '$ ' . number_format($recarga->monto,2,',','.');?><br>
                                <?php echo $recarga->nombre . ' ' . $recarga->apellido;?><br>
                                
                            </div>
                        </div>
                            <img src="<?php echo $recarga->comprobante;?>" alt="Comprobante" class="col-lg-offset-1 col-lg-10 col-md-12 col-sm-12">;
                        <br>
            </div>   
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="col-lg-12">
    <p>
                    <?php if($recarga->estado  == ESTADO_RECARGA_ESPERA && $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?>
        <a href="Recargas/procesar?id=<?php echo $recarga->id;?>&estado=<?php echo ESTADO_RECARGA_ACEPTADA;?>" class="btn btn-primary btn-outline">Aceptar</a>
        <a href="Recargas/procesar?id=<?php echo $recarga->id;?>&estado=<?php echo ESTADO_RECARGA_RECHAZADA;?>" class="btn btn-danger btn-outline">Rechazada</a>
                    <?php }?>
        <a href="Recargas?tienda=<?php echo $tienda;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>" class="btn btn-primary pull-right btn-outline">Volver</i></a>
    </p>
</div>