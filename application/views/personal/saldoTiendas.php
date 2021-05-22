<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Saldo Disponible en Tiendas</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Tiendas
            </div>
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>
                            <th>Tienda</th>
                            <th>Saldo Disponible</th>
                            <th>Saldo en Bolivares</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR){
                            $i = 1;
                            foreach($tiendas as $tienda){
                                if ($i%2!=0){
                                    ?>
                                    <tr class="odd">
                                        <td><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></td>
                                        <td><?php echo '$COP ' . number_format($tienda->pesos,2,',','.');?></td>
                                        <td><?php echo 'BsS ' . number_format($tienda->pesos/$tasa->valor,2,',','.');?></td>
                                    </tr>
                        
                        <?php   }else{?>
                                    <tr class="even">
                                        <td><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></td>
                                        <td><?php echo '$COP ' . number_format($tienda->pesos,2,',','.');?></td>
                                        <td><?php echo 'BsS ' . number_format($tienda->pesos/$tasa->valor,2,',','.');?></td>
                                    </tr>
                        <?php   
                                }
                                $i =+1;
                            }
                            }else{
                                        ?>
                                        <tr class="odd">
                                            <td><?php echo $tiendas->nombre . ' ' . $tiendas->apellido;?></td>
                                            <td><?php echo '$COP ' . number_format($tiendas->pesos,2,',','.');?></td>
                                            <td><?php echo 'BsS ' . number_format($tiendas->pesos/$tasa->valor,2,',','.');?></td>
                                        </tr>
                        <?php
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