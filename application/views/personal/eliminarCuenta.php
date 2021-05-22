<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Eliminar Usuario</h1>
    </div>
    <!-- /.col-lg-12 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"> 
                    Usuario
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="alert alert-danger alert-dismissable col-md-offset-3 col-md-5">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ¿Seguro que desea eliminar esta cuenta?
                        </div>
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-offset-3 col-sm-12 col-lg-6">
                            <div class="col-lg-6">
                                <strong>Banco</strong><br>
                                <strong>Cuenta</strong><br>
                                <strong>Tipo de Cuenta</strong><br>
                                <strong>Titular</strong><br>
                                <strong>Cedula</strong><br>
                                <strong>Girador</strong><br>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $cuenta->banco;?><br>
                                <?php echo $cuenta->numero;?><br>
                                <?php echo $cuenta->tipo;?><br>
                                <?php echo $cuenta->nombre;?><br>
                                <?php echo $cuenta->cedula;?><br>
                                <?php echo $cuenta->nombreGirador . ' ' . $cuenta->apellidoGirador;?><br>
                            </div>
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
<div class="row">
    <div class="col-lg-offset-4 col-lg-12">
        <a href="Usuarios/deleteCuenta?cuenta=<?php echo $cuenta->numero;?>" class="btn btn-danger">Eliminar</a>
        <a href="Usuarios/detalles?usuario=<?php echo $cuenta->girador;?>" class="btn btn-default">Volver</a>
    </div>
</div>