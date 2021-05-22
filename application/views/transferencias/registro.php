<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Transferencias</h1>
    </div>
    <!-- /.col-lg-12 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-green">
                <div class="panel-heading"> 
                    Transferencia
                </div>
                <div class="panel-body">
                    <div class="row">                   
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-sm-12 col-xs-12">
                            <?php 
                                if(isset($error)){
                                    echo $error;
                                }
                                echo form_open_multipart('Transferencias/registrar','role="form" method="post" id="registrarTransferencias"');
                            ?>
                            <!-- <form role="form" method="post" action="Transferencias/registrar" id="registrarTransferencias"> -->
                                <div class="form-group">
                                    <label>Numero de Referencia</label>
                                    <input class="form-control" name="referencia" id="referencia" type="text" placeholder="Numero de Referencia" required>
                                </div>
                                <!--Crear una lista desplegable en donde se presenten solo las cuentas del girador-->
                                <div class="form-group">
                                    <label>Capture</label>
                                    <input type="file" name="capture" id="capture" required>
                                    <img id="vistaPrevia" src="https://via.placeholder.com/468x60?text=Capture+de+la+Transferencia" alt="Capture de la Transferencia" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"/>
                                </div>
                                <div class="form-group">
                                    <label>Transaccion</label>
                                    <!-- <input class="form-control" name="transaccion" type="number" placeholder="Numero de transaccion" require> -->
                                    <select class="form-control" name="transaccion" required>
                                        <option value="">Seleccione una Transacción</option>
                                        <?php 
                                            foreach ($transacciones as $transaccion) {
                                        ?>
                                            <option value="<?php echo $transaccion->referenciaTransaccion;?>"><?php echo $transaccion->referenciaTransaccion . " - " . $transaccion->nombre . ' - Bs.' . $transaccion->monto?></option>
                                        <?php    
                                            }    
                                        ?>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-default" id="botonRegistrar">Registrar</button>
                                <button type="reset" class="btn btn-danger">Borrar Todo</button>
                            </form>
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
</div>
