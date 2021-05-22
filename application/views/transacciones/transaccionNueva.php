<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Transacciones</h1>
    </div>
    <!-- /.col-lg-12 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-yellow">
                <div class="panel-heading"> 
                    Transaccion
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            <div class="col-lg-6">
                                <strong>Fecha</strong><br>
                                <strong>N° Referencia</strong><br>
                                <strong>Beneficiario</strong><br>
                                <strong>Cedula</strong><br>
                                <strong>Banco</strong><br>
                                <strong>Numero de Cuenta</strong><br>
                                <strong>Monto</strong><br>
                                <strong>Tasa</strong><br>
                                <strong>Pesos</strong><br>
                                <strong>Estado</strong><br>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $transaccion->fecha;?><br>
                                <?php echo $transaccion->referencia;?><br>
                                <?php echo $transaccion->beneficiario;?><br>
                                <?php echo $transaccion->cedula;?><br>
                                <?php echo $transaccion->banco;?><br>
                                <?php echo $transaccion->cuenta;?><br>
                                <?php echo $transaccion->monto;?><br>
                                <?php echo $transaccion->tasa;?><br>
                                <?php echo $transaccion->pesos;?><br>
                                <?php echo $transaccion->estado;?><br>
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
</div>