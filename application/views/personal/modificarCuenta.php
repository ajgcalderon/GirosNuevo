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
                            <form role="form" method="post" action="usuarios/updateCuenta" id="registrarCuenta">
                                <input class="form-control" name="numeroCuentaAnterior" id="numeroCuentaAnterior" type="text" value="<?php echo $cuenta->numero;?>" style="display: none;" required>
                                <div class="form-group">
                                    <label>Numero de Cuenta</label>
                                    <input class="form-control" name="numero" id="numero" type="text" value="<?php echo $cuenta->numero;?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Tipo de Cuenta</label>
                                    <select class="form-control" name="tipoCuenta" id="tipoCuenta">
                                        <option value="Ahorros" <?php if($cuenta->tipo == 'Ahorros'){echo 'selected';}?>>Ahorros</option>
                                        <option value="Corriente" <?php if($cuenta->tipo == 'Corriente'){echo 'selected';}?>>Corriente</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nombre del Titular</label>
                                    <input class="form-control" name="nombre" id="nombre" type="text" value="<?php echo $cuenta->nombre;?>" required>
                                </div>
                                <div class="form-group form-inline">
                                    <label>Cedula del Titular</label>
                                    <select class="form-control" name="nacionalidad" id="nacionalidad">
                                        <option value="V" <?php if(substr($cuenta->cedula,0,1) == 'V'){echo 'selected';}?>>V</option>
                                        <option value="E" <?php if(substr($cuenta->cedula,0,1) == 'E'){echo 'selected';}?>>E</option>
                                        <option value="J" <?php if(substr($cuenta->cedula,0,1) == 'J'){echo 'selected';}?>>J</option>
                                    </select>
                                    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" value="<?php echo substr($cuenta->cedula,2);?>" required>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="girador" id="girador">
                                        <?php 
                                            foreach ($giradores as $girador) {
                                                if($girador->id == $cuenta->girador){
                                        ?>
                                                    <option value="<?php echo $girador->id;?>" selected><?php echo $girador->nombre . ' ' . $girador->apellido . ' ' . $girador->cedula;?></option>
                                        <?php
                                                }else{
                                        ?>
                                                    <option value="<?php echo $girador->id;?>"><?php echo $girador->nombre . ' ' . $girador->apellido . ' ' . $girador->cedula;?></option>
                                        <?php
                                                }
                                            }    
                                        ?>
                                    </select>
                                </div>
                                <center>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                    <a href="Usuarios/detalles?usuario=<?php echo $cuenta->girador;?>" class="btn btn-default">Volver</a>
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