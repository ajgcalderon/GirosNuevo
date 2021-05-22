<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading"> 
                    Registro de Tienda
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            <?php
                                echo validation_errors();
                            ?>
                            <form role="form" method="post" action="usuarios/registrarPersonal" id="registrarGirador">
                                <div class="form-group" style="display: none">
                                    <label >Tienda</label>
                                    <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre" value="Tienda" required>
                                </div>
                                <div class="form-group">
                                    <label>Numero de Tienda</label>
                                    <input class="form-control" name="apellido" id="apellido" type="number" placeholder="Numero de la Tienda" required>
                                </div>
                                <div class="form-group form-inline">
                                    <label>Rif</label>
                                    <select class="form-control" name="nacionalidad" id="nacionalidad" readonly>
                                        <option value="J">J</option>
                                    </select>
                                    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" placeholder="RIF"">
                                </div>
                                <div class="form-group">
                                    <label>Grupo</label>
                                    <select class="form-control" name="grupo" id="grupo">
                                        <option value="">Seleccione un Grupo</option>
                                        <option value="<?php echo GRUPO_TIENDAS_KAREN;?>">Sin Recarga</option>
                                        <option value="<?php echo GRUPO_TIENDAS_RECARGAS;?>">Por Recarga</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nombre de Usuario</label>
                                    <input class="form-control" name="nombreUsuario" id="nombreUsuario" type="text" placeholder="Nombre de Usuario" required>
                                </div>
                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <input class="form-control" name="password" id="password" type="password" placeholder="Contraseña" required>
                                </div>
                                <div class="form-group">
                                    <label>Comision</label>
                                    <input class="form-control" name="comision" id="comision" type="comision" required>
                                </div>
                                    <input class="form-control" name="tipoUsuario" id="tipoUsuario" type="text" value="4" style="display: none">
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