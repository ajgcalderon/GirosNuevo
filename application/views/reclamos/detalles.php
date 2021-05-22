<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Reclamos</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-red">
            <div class="panel-heading">
                Información Completa de la Transaccion
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <center><h2 class="text-dark">Transaccion</h2></center>
                <?php if(isset($error)){ ?>
                    <div class="alert alert-danger col-lg-4 col-lg-offset-4"><center><?php echo $error;?></center></div>
                <?php }?>
                        <!-- /.col-lg-6 (nested) -->
                    <div class="col-lg-offset-3 col-lg-6 row">
                        <div class="col-lg-6">
                            <strong>Referencia</strong><br>
                            <strong>Beneficiario</strong><br>
                            <strong>Cuenta</strong><br>
                            <strong>Banco</strong><br>
                            <strong>Monto</strong><br>
                            <strong>Tasa</strong><br>
                            <strong>Pesos</strong><br><br>
                            <strong>Reclamo</strong><br>
                            <?php if($transaccion->estadoReclamo == RECLAMO_ESTADO_RESUELTO){?>
                            <strong>Respuesta</strong><br>
                            <?php }?>
                        </div>
                        <div class="col-lg-6">
                            <?php echo $transaccion->referencia; ?><br>
                            <?php echo $transaccion->beneficiario; ?><br>
                            <?php echo substr($transaccion->cuenta,0,4) . '-' . substr($transaccion->cuenta,4,4) . '-' . substr($transaccion->cuenta,8,4) . '-' . substr($transaccion->cuenta,12,4) . '-' . substr($transaccion->cuenta,16,4); ?><br>
                            <?php echo $transaccion->banco; ?><br>
                            <?php echo 'Bs.S ' . number_format($transaccion->monto,2,',','.'); ?><br>
                            <?php echo $transaccion->tasa; ?><br>
                            <?php echo '$ ' . number_format($transaccion->pesos,2,',','.'); ?><br><br>
                            <?php echo $transaccion->reclamo; ?><br>
                            <?php if($transaccion->estadoReclamo == RECLAMO_ESTADO_RESUELTO){?>
                            <?php echo $transaccion->respuesta; ?><br>
                            <?php }?><br><br>
                        </div>
                    </div>
                        <div class="divider row"></div>
                        <?php if($transaccion->estadoReclamo != RECLAMO_ESTADO_RESUELTO){?>

                <div class="col-lg-offset-3 col-lg-6 row">
                    <form action="Reclamos/respuesta" method="get">
                        <div class="form-group col-lg-1" style="display: none;">
                            <input type="text" name="transaccion" id="transaccion" value="<?php echo $transaccion->referencia;?>" style="display: none;" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="error">Estado Nuevo</label>
                            <select class="form-control" name="error" id="error">
                                <option value="">Seleccione una Estado</option>
                                <option value="<?php echo ESTADO_TRANSACCION_PROCESADO;?>"><?php echo ESTADO_TRANSACCION_PROCESADO;?></option>
                                <option value="<?php echo ESTADO_TRANSACCION_VERIFICACION;?>"><?php echo ESTADO_TRANSACCION_VERIFICACION;?></option>
                                <option value="<?php echo ESTADO_TRANSACCION_CANCELADO;?>"><?php echo ESTADO_TRANSACCION_CANCELADO;?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="motivo">Respuesta</label>
                            <textarea name="motivo" id="motivo" class="form-control" rows="3"  maxlength="255"></textarea>
                        </div>
                        <center><button type="submit" class="btn btn-primary">Enviar</button></center>
                    </form>
                </div>
                            <?php }?>

            </div>   
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="col-lg-12">
    <a href="Reclamos?tienda=<?php echo $tienda;?>&estado=<?php echo $estado;?>" class="btn btn-primary pull-right btn-outline">Volver</i></a>
</div>
