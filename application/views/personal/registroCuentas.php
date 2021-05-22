<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Cuentas</h1>
    </div>
    <!-- /.col-lg-12 -->
    <?php
    
        if (isset($mensaje)){
    ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Datos Erroneos, por favor verifique los datos y vuelva a enviarlos.
            </div>
        </div>
    </div>
        <?php }?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading"> 
                    Cuenta Bancaria
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            <?php
                                echo validation_errors();
                            ?>
                            <form role="form" method="post" action="usuarios/validar" id="registrarCuenta">
                                <div class="form-group">
                                    <label>Numero de Cuenta</label>
                                    <input class="form-control" name="numero" id="numero" type="text" placeholder="Numero de Cuenta" required>
                                </div>
                                <!-- <div class="form-group">
                                    <label>Banco</label>
                                    <select class="form-control" name="banco" id="banco">
                                        <option value="">Seleccione un Banco</option>
                                        <option value="100% Banco">100% Banco</option>
                                        <option value="Banco Bicentenario">Banco Bicentenario</option>
                                        <option value="Banco Central de Venezuela">Banco Central de Venezuela</option>
                                        <option value="Banco de Venezuela S.A.I.C.A">Banco de Venezuela S.A.I.C.A</option>
                                        <option value="Banco del Caribe C.A.">Banco del Caribe C.A.</option>
                                        <option value="Banco Fondo Comun">Banco Fondo Comun (BFC)</option>
                                        <option value="Banco Mercantil">Banco Mercantil</option>
                                        <option value="Banco Nacional de Credito">Banco Nacional de Credito (BNC)</option>
                                        <option value="Banco Occidental de Descuentos">Banco Occidental de Descuentos</option>
                                        <option value="Banco Provincial">Banco Provincial</option>
                                        <option value="Banco Exterior">Banco Exterior</option>
                                        <option value="Banco del Tesoro">Banco del Tesoro</option>
                                        <option value="Bancrecer">Bancrecer</option>
                                        <option value="Banesco">Banesco</option>
                                        <option value="Sofitasa">Sofitasa</option>
                                    </select>
                                </div> -->
                                <div class="form-group">
                                    <label>Tipo de Cuenta</label>
                                    <select class="form-control" name="tipoCuenta" id="tipoCuenta">
                                        <option value="Seleccion">Seleccione un Tipo de Cuenta</option>
                                        <option value="Ahorros">Ahorros</option>
                                        <option value="Corriente">Corriente</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nombre del Titular</label>
                                    <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre del Titular" required>
                                </div>
                                <div class="form-group form-inline">
                                    <label>Cedula del Titular</label>
                                    <select class="form-control" name="nacionalidad" id="nacionalidad">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                        <option value="J">J</option>
                                    </select>
                                    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" placeholder="Cedula del Titular" required>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="girador" id="girador">
                                        <option value="">Seleccione una Girador</option>
                                        <?php 
                                            foreach ($giradores as $girador) {
                                        ?>
                                            <option value="<?php echo $girador->id;?>"><?php echo $girador->nombre . ' ' . $girador->apellido . ' ' . $girador->cedula;?></option>
                                        <?php    
                                            }    
                                        ?>
                                    </select>
                                </div>
                                <center>
                                    <button type="submit" class="btn btn-default">Registrar</button>
                                    <button type="reset" class="btn btn-danger">Borrar Todo</button>
                                </center>
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

<!-- 4d186321c1a7f0f354b297e8914ab240 -->
<!-- 4d186321c1a7f0f354b297e8914ab240 -->
<!-- f688ae26e9cfa3ba6235477831d5122e -->
<!-- 8d34201a5b85900908db6cae92723617 -->
<!-- 8d34201a5b85900908db6cae92723617 -->