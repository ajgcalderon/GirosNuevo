<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Personal</h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading"> 
                    Registro de Personal
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div id="mensaje"></div>
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <form id="registroPersonal" role="form" method="post" action="Usuarios/registrarPersonal">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Usuario</label>
                                    <select class="form-control" name="tipoUsuario" id="tipoUsuario">
                                        <option value="">Seleccione el Tipo de Usuario</option>
                                        <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?>
                                        <option value="<?php echo USUARIO_ADMINISTRADOR;?>"><?php echo USUARIO_ADMINISTRADOR;?></option>
                                        <option value="<?php echo USUARIO_DISTRIBUIDOR;?>"><?php echo USUARIO_DISTRIBUIDOR;?></option>
                                        <option value="<?php echo USUARIO_GIRADOR;?>"><?php echo USUARIO_GIRADOR;?></option>
                                        <?php }?>
                                        <?php if($this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR){?><option value="<?php echo USUARIO_SUBDISTRIBUIDOR;?>"><?php echo USUARIO_SUBDISTRIBUIDOR;?></option><?php }?>
                                        <option value="<?php echo USUARIO_TIENDA;?>"><?php echo USUARIO_TIENDA;?></option>
                                    </select>
                                </div>
                                <div id="formulario"></div>
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