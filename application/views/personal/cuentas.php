<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Cuentas</h1>
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
            <?php 
                if ($this->session->userdata('tipo') != 3) {
            ?>
            
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>
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
                                        <td><?php echo $cuenta->banco;?></td>
                                        <td><?php echo $cuenta->numero;?></td>
                                        <td><?php echo $cuenta->tipo;?></td>
                                        <td><?php echo $cuenta->nombreTitular;?></td>
                                        <td><?php echo $cuenta->nombreGirador . ' ' . $cuenta->apellidoGirador;?></td>
                                        <td><?php echo 'Bs.S ' . $cuenta->saldo;?></td>
                                    </tr>
                        
                        <?php   }else{?>
                                    <tr class="even">
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
                <!-- /.table-responsive -->
            </div>
            <?php
                } else{
                    ?>
                    <div class="panel-body">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Banco</th>
                                    <th>Cuenta</th>
                                    <th>Tipo</th>
                                    <th>Titular</th>
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
                                                <td><?php echo $cuenta->banco;?></td>
                                                <td><?php echo $cuenta->numero;?></td>
                                                <td><?php echo $cuenta->tipo;?></td>
                                                <td><?php echo $cuenta->nombreTitular;?></td>
                                                <td><?php echo 'Bs.S ' . $cuenta->saldo;?></td>
                                            </tr>
                                
                                <?php   }else{?>
                                            <tr class="even">
                                                <td><?php echo $cuenta->banco;?></td>
                                                <td><?php echo $cuenta->numero;?></td>
                                                <td><?php echo $cuenta->tipo;?></td>
                                                <td><?php echo $cuenta->nombreTitular;?></td>
                                                <td><?php echo 'Bs.S ' . $cuenta->saldo;?></td>
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

            <?php
                }
            ?>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div>
    
</div>