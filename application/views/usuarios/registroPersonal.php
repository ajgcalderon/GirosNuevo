<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Personal</h1>
    </div>
    <!-- /.col-lg-12 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"> 
                    Trabajador
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            <?php
                                echo validation_errors();
                            ?>
                            <form role="form" method="post" action="usuarios/validar">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input class="form-control" name="nombre" type="text" placeholder="Nombre" required>
                                </div>
                                <div class="form-group">
                                    <label>Apellido</label>
                                    <input class="form-control" name="apellido" type="text" placeholder="Apellido" required>
                                </div>
                                <div class="form-group form-inline">
                                    <label>Cedula</label>
                                    <select class="form-control" name="nacionalidad">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                    </select>
                                    <input class="form-control" name="numeroCedula" type="text" placeholder="Cedula del Beneficiario" required>
                                </div>
                                <div class="form-group">
                                    <label>Telefono</label>
                                    <input class="form-control" name="telefono" type="text" placeholder="Telefono" required>
                                </div>
                                <div class="form-group">
                                    <label>Nombre de Usuario</label>
                                    <input class="form-control" name="usuario" type="text" placeholder="Nombre de Usuario" required>
                                </div>
                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <input class="form-control" name="password" type="password" placeholder="Contraseña" required>
                                </div>
                                <div class="form-group">
                                    <label>Cargo</label>
                                    <select class="form-control" name="tipoCuenta">
                                        <option value="Seleccion">Seleccione un Cargo</option>
                                        <option value="1">Administador</option>
                                        <option value="2">Supervisor</option>
                                        <option value="3">Girador</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cargo</label>
                                    <select class="form-control" name="tipoCuenta">
                                        <option value="Seleccion">Seleccione un Cargo</option>
                                        <?php 
                                        foreach ($supervisores as $supervisor) {
                                        ?>
                                        <option value="<?php echo $supervisor->id;?>"><?php echo $supervisor->nombre . ' ' . $supervisor->apellido;?></option>

                                        <?php }?>
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

<!-- 4d186321c1a7f0f354b297e8914ab240 -->
<!-- 4d186321c1a7f0f354b297e8914ab240 -->
<!-- f688ae26e9cfa3ba6235477831d5122e -->
<!-- 8d34201a5b85900908db6cae92723617 -->
<!-- 8d34201a5b85900908db6cae92723617 -->