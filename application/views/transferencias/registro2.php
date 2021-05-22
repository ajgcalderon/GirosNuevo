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
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                        <?php
                            echo validation_errors();
                            echo form_open('Transferencias/validar');
                        ?>
                            <!-- <form role="form" method="post" action="Transferencias/registrar"> -->
                                <div class="form-group">
                                    <label>Numero de Referencia</label>
                                    <input class="form-control" name="referencia" type="number" placeholder="Numero de Referencia" >
                                </div>
                                <div class="form-group">
                                    <label>Cuenta Debitada</label>
                                    <select class="form-control" name="cuenta">
                                        <option value="">Seleccione una cuenta</option>
                                        <?php 
                                            foreach ($cuentas as $cuenta) {
                                        ?>
                                            <option value="<?php echo $cuenta->numero;?>"><?php echo $cuenta->banco . ' - ' . $cuenta->tipo . ' - **' . substr($cuenta->numero,16,4);?></option>
                                        <?php    
                                            }    
                                        ?>
                                    </select>
                                </div>
                                <!--Crear una lista desplegable en donde se presenten solo las cuentas del girador-->
                                <div class="form-group">
                                    <label>Capture</label>
                                    <input type="file" name="imagen" id="imagen">
                                </div>
                                <div class="form-group">
                                    <label>Transaccion</label>
                                    <!-- <input class="form-control" name="transaccion" type="number" placeholder="Numero de transaccion" require> -->
                                    <select class="form-control" name="transaccion">
                                        <option value="">Seleccione una cuenta</option>
                                        <?php 
                                            foreach ($transacciones as $transaccion) {
                                        ?>
                                            <option value="<?php echo $transaccion->referenciaTransaccion;?>"><?php echo $transaccion->referenciaTransaccion . " - " . $transaccion->nombre . ' - Bs.' . $transaccion->monto?></option>
                                        <?php    
                                            }    
                                        ?>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-default">Registrar</button>
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