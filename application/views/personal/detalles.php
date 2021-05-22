<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Personal</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Personal
            </div>
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Grupo</th>
                            <th>Comisiones</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="odd">
                            <td><?php echo $usuario->nombre . ' ' . $usuario->apellido;?></td>
                            <td>
                                <?php   
                                    switch($usuario->tipo){
                                        case USUARIO_ADMINISTRADOR:
                                            echo 'Administrador';
                                            break;
                                        case USUARIO_SUPERVISOR:
                                            echo 'Supervisor';
                                            break;
                                        case USUARIO_GIRADOR:
                                            echo 'Girador';
                                            break;
                                        case USUARIO_TIENDA:
                                            echo 'Tienda';
                                            break;
                                    }
                                ?>
                            </td>
                            <td><?php echo $usuario->grupo;?></td>
                            <td><?php echo $usuario->comision . '%';?></td>
                            <td><center><a href="Usuarios/modificar?usuario=<?php echo $usuario->id; ?>" class="btn btn-default"><span class="fa fafw fa-edit"></span></a></center></td>
                            <td><center><a href="Usuarios/eliminar?usuario=<?php echo $usuario->id; ?>" class="btn btn-default"><span class="fa fafw fa-times"></span></a></center></td>
                        </tr>
                        
                    </tbody>
                    
                </table>
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>
                            <th>Banco</th>
                            <th>Cuenta</th>
                            <th>Tipo</th>
                            <th>Titular</th>
                            <th>Girador</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            foreach($cuentas as $cuenta){
                                if ($i%2!=0){
                                    ?>
                                    <tr class="odd">
                                        <td><?php echo $cuenta->banco;?></td>
                                        <td><?php echo $cuenta->numero;?></td>
                                        <td><?php echo $cuenta->tipo;?></td>
                                        <td><?php echo $cuenta->nombreTitular;?></td>
                                        <td><?php echo $cuenta->nombreGirador . ' ' . $cuenta->apellidoGirador;?></td>
                                        <td><center><a href="Usuarios/modificarCuenta?cuenta=<?php echo $cuenta->numero; ?>" class="btn btn-default"><span class="fa fafw fa-edit"></span></a></center></td>
                                        <td><center><a href="Usuarios/eliminarCuenta?cuenta=<?php echo $cuenta->numero; ?>" class="btn btn-default"><span class="fa fafw fa-times"></span></a></center></td>
                                    </tr>
                        
                        <?php   }else{?>
                                    <tr class="even">
                                        <td><?php echo $cuenta->banco;?></td>
                                        <td><?php echo $cuenta->numero;?></td>
                                        <td><?php echo $cuenta->tipo;?></td>
                                        <td><?php echo $cuenta->nombreTitular;?></td>
                                        <td><?php echo $cuenta->nombreGirador . ' ' . $cuenta->apellidoGirador;?></td>
                                        <td><center><a href="Usuarios/modificarCuenta?cuenta=<?php echo $cuenta->numero; ?>" class="btn btn-default"><span class="fa fafw fa-edit"></span></a></center></td>
                                        <td><center><a href="Usuarios/eliminarCuenta?cuenta=<?php echo $cuenta->numero; ?>" class="btn btn-default"><span class="fa fafw fa-times"></span></a></center></td>
                                    </tr>
                        <?php   
                                }
                                $i =+1;
                            }
                        ?>
                        
                    </tbody>
                    
                </table>
                <!-- /.table-responsive -->
            </div>
               
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div>
    
</div>