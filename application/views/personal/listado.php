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
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Distribuidor</th>
                            <th>Subdistribuidor</th>
                            <th>Comisiones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            foreach($usuarios as $usuario){
                                if ($i%2!=0){
                                    ?>
                                    <tr class="odd">
                                        <td><a href="Usuarios/detalles?usuario=<?php echo $usuario->id;?>"><?php echo $usuario->nombre . ' ' . $usuario->apellido;?></a></td>
                                        <td><?php echo $usuario->tipo;?></td>
                                        <td><?php echo $usuario->grupo;?></td>
                                        <td><?php echo $usuario->subgrupo;?></td>
                                        <td><?php echo $usuario->comision . '%';?></td>
                                    </tr>
                        
                        <?php   }else{?>
                                    <tr class="even">
                                        <td><a href="Usuarios/detalles?usuario=<?php echo $usuario->id;?>"><?php echo $usuario->nombre . ' ' . $usuario->apellido;?></a></td>
                                        <td><?php echo $usuario->tipo;?></td>
                                        <td><?php echo $usuario->grupo;?></td>
                                        <td><?php echo $usuario->subgrupo;?></td>
                                        <td><?php echo $usuario->comision . '%';?></td>
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