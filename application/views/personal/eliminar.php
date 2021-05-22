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
                            ¿Seguro que desea eliminar este usuario?
                        </div>
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-offset-3 col-sm-12 col-lg-6">
                            <div class="col-lg-6">
                                <strong>Nombre</strong><br>
                                <strong>Cedula</strong><br>
                                <strong>Nombre de Usuario</strong><br>
                                <strong>Tipo de Usuario</strong><br>
                                <strong>Grupo</strong><br>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $usuario->nombre . ' ' . $usuario->apellido;?><br>
                                <?php echo $usuario->cedula;?><br>
                                <?php echo $usuario->nombreUsuario;?><br>
                                <?php   
                                    switch($usuario->tipo){
                                        case 1:
                                            echo 'Administrador';
                                            break;
                                        case 2:
                                            echo 'Supervisor';
                                            break;
                                        case 3:
                                            echo 'Girador';
                                            break;
                                        case 4:
                                            echo 'Tienda';
                                            break;
                                    }
                                ?><br>
                                <?php echo $usuario->grupo;?><br>
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
        <a href="Usuarios/delete?usuario=<?php echo $usuario->id;?>" class="btn btn-danger">Eliminar</a>
        <a href="Usuarios/detalles?usuario=<?php echo $usuario->id;?>" class="btn btn-default">Volver</a>
    </div>
</div>