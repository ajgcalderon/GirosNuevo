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
                        <div id="mensaje"></div>
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            <form role="form" id="registroTransacciones" method="post" action="Transacciones/registrar">
                                <div class="form-group" id="group-pesos">
                                    <label class="control-label">Valor en Pesos</label>
                                    <input class="form-control" name="pesos" id="pesos" type="number" placeholder="$COP" min="20000" required>
                                    <?php if($this->session->userdata('tipo') != USUARIO_ADMINISTRADOR && $this->session->userdata('grupo') != GRUPO_EMPRESA){?><p name="saldo" id="saldo" class="text-success">Saldo Disponible: $COP <?php echo number_format($cuenta->pesos,2,',','.');?></p>
                                    <input type="text" name="disponible" id="disponible" style="display: none" value="<?php echo $cuenta->pesos;?>"><?php }?>
                                </div>
                                <div class="form-group" >
                                    <label class="control-label">Tasa</label>
                                    <input class="form-control" name="tasa" id="tasa" type="text" placeholder="Tasa" value="<?php echo $tasa->valor;?>" readonly>
                                    <?php if($this->session->userdata('tipo') == USUARIO_TIENDA && $this->session->userdata('grupo')==GRUPO_TIENDAS_RECARGAS){?><input class="form-control" name="saldoDisponible" id="saldoDisponible" type="number" style="display: none" value="<?php echo $cuenta->pesos;?>" readonly><?php }?>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Monto (Bs.S)</label>
                                    <input class="form-control" name="monto" id="monto" type="float" value="0" required readonly>
                                </div>
                                <h3><strong>Datos del Beneficiario en Venezuela</strong></h3>
                                <div class="form-group form-inline">
                                    <label class="control-label">Cedula</label>
                                    <select class="form-control" name="nacionalidad" id="nacionalidad" >
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                    </select>
                                    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" placeholder="Cedula del Beneficiario" required>
                                </div>
                                <div id="infoBeneficiario">
                                    <div class="form-group">
                                        <label class="control-label">Nombre del Beneficiario</label>
                                        <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre del Beneficiario" required>
                                    </div>
                                    <div class="form-group" id="cuenta-group">
                                        <label class="control-label" for="cuenta">Numero de Cuenta</label>
                                        <input class="form-control" name="cuenta" id="cuenta" type="number" placeholder="Numero de Cuenta" minlength="20" maxlength="20" required>
                                        <div id="banco"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Tipo de Cuenta</label>
                                        <select class="form-control" name="tipoCuenta" id="tipoCuenta" required>
                                            <option value="Seleccion">Seleccione un Tipo de Cuenta</option>
                                            <option value="Ahorros">Ahorros</option>
                                            <option value="Corriente">Corriente</option>
                                        </select>
                                    </div>
                                </div>
                                <h3><strong>Datos del Cliente en Colombia</strong></h3>
                                    <div class="form-group form-inline">
                                        <label class="control-label">Cedula / RIF</label>
                                        <select class="form-control" name="nacionalidadCliente" id="nacionalidadCliente">
                                            <option value="C">C</option>
                                            <option value="E">E</option>
                                            <option value="J">J</option>
                                        </select>
                                        <input class="form-control" name="numeroCedulaCliente" id="numeroCedulaCliente" type="text" placeholder="Cedula del Cliente" required>
                                    </div>
                                <div id="infoCliente">
                                    <div class="form-group">
                                        <label class="control-label">Nombre</label>
                                        <input class="form-control" name="nombreCliente" id="nombreCliente" type="text" placeholder="Nombre del cliente" required>
                                    </div>
                                    <div class="form-group form-inline">
                                        <label class="control-label">Telefono</label>
                                        <select class="form-control" name="codArea" id="codArea" >
                                            <option value="+57">+57 - Colombia</option>
                                            <option value="+58">+58 - Venezuela</option>
                                        </select>
                                        <input class="form-control" name="telefono" id="telefono" type="text" placeholder="Numero de telefono" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-default" id="botonRegistrar" disabled>Registrar</button>
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
