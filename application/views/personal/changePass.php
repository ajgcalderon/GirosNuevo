<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading"> 
                    Actualizar Usuario
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div id="mensaje"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            <?php
                                echo validation_errors();
                            ?>
                            <form role="form" method="post" action="Usuarios/actualizarPass" id="changePass">
                                
                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <input class="form-control" name="password" id="password" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <label>Confirmar Contraseña</label>
                                    <input class="form-control" name="passwordConfirm" id="passwordConfirm" type="password" value="">
                                </div>
                                <button type="submit" class="btn btn-default" id="btn-submit" disabled>Actualizar</button>
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