<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading"> 
                    Actualizar Usuario
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            <?php
                                echo validation_errors();
                            ?>
                            <form role="form" method="post" action="usuarios/actualizarPersonal" id="registrarGirador">
                                <input class="form-control" name="id" id="id" type="text" value="<?php echo $usuario->id;?>" style="display: none;" required>
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input class="form-control" name="nombre" id="nombre" type="text" value="<?php echo $usuario->nombre;?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Apellido</label>
                                    <input class="form-control" name="apellido" id="apellido" type="text" value="<?php echo $usuario->apellido;?>" required>
                                </div>
                                <div class="form-group form-inline">
                                    <label>Cedula</label>
                                    <select class="form-control" name="nacionalidad" id="nacionalidad">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                    </select>
                                    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" value="<?php echo substr($usuario->cedula,2);?>" required>
                                </div>
                                <?php
                                    switch($usuario->tipo){
                                        case USUARIO_ADMINISTRADOR:
                                ?>
                                            <input class="form-control" name="grupo" id="grupo" type="text" value="<?php echo $usuario->grupo;?>" style="display: none;" required>
                                <?php
                                            break;
                                        case USUARIO_DISTRIBUIDOR:
                                ?>
                                            <input class="form-control" name="grupo" id="grupo" type="text" value="<?php echo $usuario->grupo;?>" style="display: none;" required>
                                <?php
                                            break;
                                        case USUARIO_SUPERVISOR:
                                ?>
                                            <input class="form-control" name="grupo" id="grupo" type="text" value="<?php echo $usuario->grupo;?>" style="display: none;" required>
                                <?php
                                            break;
                                        case USUARIO_GIRADOR:
                                ?>
                                            <div class="form-group">
                                                <select class="form-control" name="grupo" id="grupo">
                                                    <?php 
                                                        foreach ($supervisores as $supervisor) {
                                                                if($supervisor->grupo == $usuario->grupo){?>
                                                                    <option value="<?php echo $supervisor->grupo;?>" selected><?php echo $supervisor->nombre . ' ' . $supervisor->apellido . ' ' . $supervisor->cedula;?></option>
                                                    <?php       }else{?>
                                                                    <option value="<?php echo $supervisor->grupo;?>"><?php echo $supervisor->nombre . ' ' . $supervisor->apellido . ' ' . $supervisor->cedula;?></option>
                                                    <?php       }   
                                                        }    
                                                    ?>
                                                </select>
                                            </div>
                                <?php
                                            break;
                                        case USUARIO_TIENDA:
                                ?>
                                            <div class="form-group">
                                                <select class="form-control" name="grupo" id="grupo">
                                                    <option value="<?php echo GRUPO_EMPRESA;?>" <?php if($usuario->grupo == GRUPO_EMPRESA){echo 'selected';}?>><?php echo GRUPO_EMPRESA;?></option>
                                                    <?php foreach ($distribuidores as $distribuidor) { ?>
                                                        <option value="<?php echo $distribuidor->grupo;?>" <?php if($usuario->grupo == $distribuidor->grupo){echo 'selected';}?>><?php echo $distribuidor->grupo;?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                <?php
                                            break;
                                        case USUARIO_SUBDISTRIBUIDOR:
                                ?>
                                            <div class="form-group">
                                                <select class="form-control" name="grupo" id="grupo">
                                                    
                                                    <?php foreach ($distribuidores as $distribuidor) { ?>
                                                        <option value="<?php echo $distribuidor->grupo;?>" <?php if($usuario->grupo == $distribuidor->grupo){echo 'selected';}?>><?php echo $distribuidor->grupo;?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                <?php
                                            break;
                                    }
                                ?>
                                <div class="form-group" <?php if($this->session->userdata('id') == $usuario->id){?> style="display: none;" <?php }?>>
                                    <label>Nombre de Usuario</label>
                                    <input class="form-control" name="nombreUsuario" id="nombreUsuario" type="text" value="<?php echo $usuario->nombreUsuario;?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Contraseña Nueva (Opcional)</label>
                                    <input class="form-control" name="password" id="password" type="password" value="">
                                </div>
                                <div class="form-group" <?php if($this->session->userdata('id') == $usuario->id){?> style="display: none;" <?php }?>>
                                    <label>Comision</label>
                                    <input class="form-control" name="comision" id="comision" type="text" value="<?php echo $usuario->comision;?>" required>
                                </div>
                                <button type="submit" class="btn btn-default">Actualizar</button>
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