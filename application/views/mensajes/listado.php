<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Listado de Mensajes</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Mensajes
            </div>
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Mensaje</th>
                            <th>Dirigido A</th>
                            <th>Estado</th>
                            <th>Hasta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            foreach($mensajes as $mensaje){
                                if ($i % 2 != 0){
                                    ?>
                                    <tr class="odd">
                                        <td><a href="Mensajes/modificar?mensaje=<?php echo $mensaje->idMensaje;?>"><?php echo $mensaje->titulo;?></a></td>
                                        <td><?php echo $mensaje->mensaje;?></td>
                                        <td><?php echo $mensaje->publico;?></td>
                                        <td><?php echo $mensaje->estado;?></td>
                                        <td><?php echo $mensaje->hasta;?></td>
                                    </tr>
                        
                        <?php   }else{?>
                                    <tr class="even">
                                        <td><a href="Mensajes/modificar?mensaje=<?php echo $mensaje->idMensaje;?>"><?php echo $mensaje->titulo;?></a></td>
                                        <td><?php echo $mensaje->mensaje;?></td>
                                        <td><?php echo $mensaje->publico;?></td>
                                        <td><?php echo $mensaje->estado;?></td>
                                        <td><?php echo $mensaje->hasta;?></td>
                                    </tr>
                        <?php   
                                }
                                $i++;
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