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
                            <form role="form" method="post" action="Mensajes/set" id="formularioMensajes">
                                <div class="form-group">
                                    <label for="titulo" class="control-label">Título</label>
                                    <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título" required>
                                </div>
                                <div class="form-group">
                                    <label for="mensaje" class="control-label">Mensaje</label>
                                    <textarea name="mensaje" id="mensaje" class="form-control" rows="3" maxlength="255" placeholder="Mensaje a Presentar" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="hasta" class="control-label">Finalizar Aviso</label>
                                    <input type="date" name="hasta" id="hasta" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="publico" class="control-label">Dirigido A:</label>
                                    <select name="publico" id="publico" class="form-control">
                                        <option value="<?php echo PUBLICO_MENSAJES_CLIENTES;?>" >Clientes</option>
                                        <option value="<?php echo PUBLICO_MENSAJES_PERSONAL;?>" >Personal</option>
                                        <option value="<?php echo PUBLICO_MENSAJES_TODOS;?>" selected>Todos</option>
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
<div class="modal fade" id="MensajeRegistro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <center><h4 class="modal-title text-primary" id="myModalLabel">AVISO</h4></center>
                    </div>
                    <div class="modal-body">
                    <ul>
                        <li>El campo "Finalizar Aviso" no es obligatorio.</li>
                        <li>Puede tener un máximo de 5 avisos activos simultáneamente.</li>
                        <li>Recuerde que los avisos deberán ser desactivados en caso de no asignar una fecha de finalización.</li>
                        <li>Los avisos se pueden modificar o desactivar haciendo click en el título dentro del listado de los mismos.</li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>