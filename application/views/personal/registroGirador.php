<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading"> 
                    Registro de Girador
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
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre" required>
                                </div>
                                <div class="form-group">
                                    <label>Apellido</label>
                                    <input class="form-control" name="apellido" id="apellido" type="text" placeholder="Apellido" required>
                                </div>
                                <div class="form-group form-inline">
                                    <label>Cedula</label>
                                    <select class="form-control" name="nacionalidad" id="nacionalidad">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                    </select>
                                    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" placeholder="Numero de Cedula" required>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="grupo" id="grupo">
                                        <option value="">Seleccione un Supervisor</option>
                                        <?php 
                                            foreach ($supervisores as $supervisor) {
                                        ?>
                                            <option value="<?php echo $supervisor->grupo;?>"><?php echo $supervisor->nombre . ' ' . $supervisor->apellido . ' ' . $supervisor->cedula;?></option>
                                        <?php    
                                            }    
                                        ?>
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
                                    <input class="form-control" name="comision" id="comision" type="text" placeholder="Comision" required>
                                </div>
                                    <input class="form-control" name="tipoUsuario" id="tipoUsuario" type="text" value="3" style="display: none">
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