<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Balance de Cuentas</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Girador</th>
                            <th>Beneficiario</th>
                            <th>Monto</th>
                            <th>Nº Referencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            foreach($transferencias as $transferencia){
                                if ($i%2!=0){
                        ?>
                                    <tr class="odd">
                                        <td><?php echo $transferencia->fecha;?></td>
                                        <td><?php echo $transferencia->girador;?></td>
                                        <td><?php echo $transferencia->beneficiario;?></td>
                                        <td><?php echo 'Bs.S ' . $transferencia->monto;?></td>
                                        <td><?php echo $transferencia->numero;?></td>
                                    </tr>
                        
                        <?php   }else{?>
                                    <tr class="even">
                                        <td><?php echo $transferencia->fecha;?></td>
                                        <td><?php echo $transferencia->girador;?></td>
                                        <td><?php echo $transferencia->beneficiario;?></td>
                                        <td><?php echo $transferencia->monto;?></td>
                                        <td><?php echo $transferencia->numero;?></td>
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