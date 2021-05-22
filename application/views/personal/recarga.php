<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Recargar Cuentas</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cuentas
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" action="Usuarios/recarga" id="recargarCuentas">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Banco</th>
                                <th>Cuenta</th>
                                <th>Tipo</th>
                                <th>Titular</th>
                                <th>Girador</th>
                                <th>Saldo Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                foreach($cuentas as $cuenta){
                                    if ($i%2!=0){
                                        ?>
                                        <tr class="odd">
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="cuentas[]" value="<?php echo $cuenta->numero;?>" id="cuentas[]">
                                                    </label>
                                                </div>
                                            </td>
                                            <td><?php echo $cuenta->banco;?></td>
                                            <td><?php echo $cuenta->numero;?></td>
                                            <td><?php echo $cuenta->tipo;?></td>
                                            <td><?php echo $cuenta->nombreTitular;?></td>
                                            <td><?php echo $cuenta->nombreGirador . ' ' . $cuenta->apellidoGirador;?></td>
                                            <td><?php echo 'Bs.S ' . $cuenta->saldo;?></td>
                                        </tr>
                            
                            <?php   }else{?>
                                        <tr class="even">
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="cuentas[]" value="<?php echo $cuenta->numero;?>">
                                                    </label>
                                                </div>
                                            </td>
                                            <td><?php echo $cuenta->banco;?></td>
                                            <td><?php echo $cuenta->numero;?></td>
                                            <td><?php echo $cuenta->tipo;?></td>
                                            <td><?php echo $cuenta->nombreTitular;?></td>
                                            <td><?php echo $cuenta->nombreGirador . ' ' . $cuenta->apellidoGirador;?></td>
                                            <td><?php echo 'Bs.S ' . $cuenta->saldo;?></td>
                                        </tr>
                            <?php   
                                    }
                                    $i =+1;
                                }
                            ?>
                            
                        </tbody>
                        
                    </table>
                    <div class="form-group">
                        <label>Monto</label>
                        <input class="form-control" name="monto" id="monto" type="text" placeholder="Monto" required>
                    </div>
                    <div class="divider">&nbsp;</div>
                    <center><button type="submit" class="btn btn-outline btn-primary btn-lg btn-block">Recargar</button></center>
                </form>
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