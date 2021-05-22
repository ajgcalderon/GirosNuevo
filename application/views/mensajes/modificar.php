<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Aviso</h1>
    </div>
    <!-- /.col-lg-12 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading"> 
                    Mensaje
                </div>
                <div class="panel-body">
                    <div class="row">
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-offset-3 col-lg-6">
                            <?php if(isset($error)){ ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <center><?php echo $error;?></center>
                                </div>
                            <?php }?>
                            <form role="form" method="post" action="Mensajes/actualizar" id="formularioMensajes">
                                <input type="text" name="id" value="<?php echo $mensaje->idMensaje;?>" style="display:none;">
                                <div class="form-group">
                                    <label for="titulo" class="control-label">Título</label>
                                    <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo $mensaje->titulo;?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="mensaje" class="control-label">Mensaje</label>
                                    <textarea name="mensaje" id="mensaje" class="form-control" rows="3" maxlength="255" placeholder="Mensaje a Presentar" required><?php echo $mensaje->mensaje;?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="hasta" class="control-label">Hasta</label>
                                    <input type="date" name="hasta" id="hasta" class="form-control" value="<?php echo $mensaje->hasta;?>">
                                </div>
                                <div class="form-group">
                                    <label for="publico" class="control-label">Dirigido A:</label>
                                    <select name="publico" id="publico" class="form-control">
                                        <option value="<?php echo PUBLICO_MENSAJES_CLIENTES;?>" <?php if($mensaje->publico == PUBLICO_MENSAJES_CLIENTES){echo 'selected';}?>>Clientes</option>
                                        <option value="<?php echo PUBLICO_MENSAJES_PERSONAL;?>" <?php if($mensaje->publico == PUBLICO_MENSAJES_PERSONAL){echo 'selected';}?>>Personal</option>
                                        <option value="<?php echo PUBLICO_MENSAJES_TODOS;?>" <?php if($mensaje->publico == PUBLICO_MENSAJES_TODOS){echo 'selected';}?>>Todos</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="estado" class="control-label">Estado</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="<?php echo ESTADO_MENSAJE_ACTIVO?>" <?php if($mensaje->estado == ESTADO_MENSAJE_ACTIVO){echo 'selected';}?>>Activo</option>
                                        <option value="<?php echo ESTADO_MENSAJE_INACTIVO?>" <?php if($mensaje->estado == ESTADO_MENSAJE_INACTIVO){echo 'selected';}?>>Inactivo</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default">Actualizar</button>
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