<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Modificar Transacciones</h1>
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
                            <form role="form" id="modificarTransacciones" method="post" action="Transacciones/verificarTransaccion">
                                <div class="form-group">
                                    <label>Referencia</label>
                                    <input class="form-control" name="referencia" id="referencia" type="text" value="<?php echo $transaccion->referencia;?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Valor en Pesos</label>
                                    <input class="form-control" name="pesos" id="pesos" type="text" value="<?php echo $transaccion->pesos;?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Tasa</label>
                                    <input class="form-control" name="tasa" id="tasa" type="text" value="<?php echo $transaccion->tasa;?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Monto (Bs.S)</label>
                                    <input class="form-control" name="monto" id="monto" type="float" value="<?php echo $transaccion->monto;?>" required readonly>
                                </div>
                                <div class="form-group form-inline">
                                    <label>Cedula</label>
                                    <select class="form-control" name="nacionalidad" id="nacionalidad">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                    </select>
                                    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" value="<?php echo substr($transaccion->cedula,2);?>" required>
                                </div>
                                <input type="text" name="idBeneficiario" id="idBeneficiario" value="<?php echo $transaccion->idBeneficiario;?>" style="display:none">
                                <div class="form-group">
                                    <label>Nombre del Beneficiario</label>
                                    <input class="form-control" name="nombre" id="nombre" type="text" value="<?php echo $transaccion->beneficiario;?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Numero de Cuenta</label>
                                    <input class="form-control" name="cuenta" id="cuenta" type="number" value="<?php echo $transaccion->cuenta;?>" minlength="20" maxlength="20">
                                </div>
                                <div class="form-group">
                                    <label>Tipo de Cuenta</label>
                                    <select class="form-control" name="tipoCuenta" id="tipoCuenta">
                                        <option value="Ahorros" <?php if($transaccion->tipoCuenta == "Ahorros"){echo 'selected';}?>>Ahorros</option>
                                        <option value="Corriente" <?php if($transaccion->tipoCuenta == "Corriente"){echo 'selected';}?>>Corriente</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default">Actualizar</button>
                                <a href="transacciones/pendientes" class="btn btn-primary">Volver</a>
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
