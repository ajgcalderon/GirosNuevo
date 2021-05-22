<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Transferencias</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                Devoluciones
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="row">
                <form action="Transferencias/devoluciones" method="get" class="form-inline col-lg-12">
                    <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                        <div class="form-group col-lg-offset-1 col-lg-3 ">
                            <div class="input-group">
                                <label for="tienda">Tienda</label>
                                <select name="tienda" id="tienda" class="form-control">
                                    <option value="">Seleccione una Tienda</option>
                                    <?php foreach ($tiendas as $tienda) { ?>
                                        <option value="<?php echo $tienda->id;?>" <?php if($tiendaSeleccionada == $tienda->id){echo "Selected";}?>><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                        <div class="form-group col-lg-3 ">
                    <?php }else{?>
                        <div class="form-group col-lg-offset-3 col-lg-3 ">
                    <?php } ?>
                            <div class="input-group">
                            <label for="desde">Desde</label>
                                <input type="date" name="desde" class="form-control">
                            </div>
                        </div>
                    <div class="form-group  col-lg-3">
                        <div class="input-group">
                            <label for="hasta">Hasta</label>
                            <input type="date" name="hasta" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><span class="fa fafw fa-search"></span></button>
                </form>
            </div>
            <br>
            <?php 
                switch ($this->session->userdata('tipo')) {
                    
                    case USUARIO_SUPERVISOR:
                ?>
                        
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Fecha Giro</th>
                                        <th>Girador</th>
                                        <th>Beneficiario</th>
                                        <th>Monto</th>
                                        <th>Tasa</th>
                                        <th>Pesos</th>
                                        <th>Transferencia</th>
                                        <th>Transacción</th>
                                        <th>Tienda</th>
                                        <th>Fecha Devolucion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($transferencias as $transferencia){
                                    ?>
                                                <tr>
                                                    <td><?php echo $transferencia->fecha;?></td>
                                                    <td><?php echo $transferencia->girador;?></td>
                                                    <td><?php echo $transferencia->beneficiario;?></td>
                                                    <td><?php echo 'Bs.S ' . number_format($transferencia->monto,2,',','.');?></td>
                                                    <td><?php echo $transferencia->tasa;?></td>
                                                    <td><?php echo '$ ' . number_format($transferencia->pesos,2,',','.');?></td>
                                                    <td><a href="Transferencias/infoCompleta?transferencia=<?php echo $transferencia->id;?>" id='transferenciasID'><?php echo $transferencia->referencia;?></a></td>
                                                    <td><?php echo $transferencia->transaccion;?></td>
                                                    <td><?php echo $transferencia->nombreTienda;?></td>
                                                    <td><?php echo $transferencia->fechaDev;?></td>
                                                </tr>
                                    
                                    <?php   
                                        }
                                    ?>

                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                    <?php
                        break;
                    case USUARIO_GIRADOR:
                    ?>
                        
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Fecha Giro</th>
                                        <th>Beneficiario</th>
                                        <th>Monto</th>
                                        <th>Tasa</th>
                                        <th>Pesos</th>
                                        <th>Transferencia</th>
                                        <th>Transaccion</th>
                                        <th>Tienda</th>
                                        <th>Fecha Devolucion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($transferencias as $transferencia){
                                    ?>
                                                <tr>
                                                    <td><?php echo $transferencia->fecha;?></td>
                                                    <td><?php echo $transferencia->beneficiario;?></td>
                                                    <td><?php echo 'Bs.S ' . number_format($transferencia->monto,2,',','.');?></td>
                                                    <td><?php echo $transferencia->tasa;?></td>
                                                    <td><?php echo '$ ' . number_format($transferencia->pesos,2,',','.');?></td>
                                                    <td><a href="Transferencias/infoCompleta?transferencia=<?php echo $transferencia->id;?>" id='transferenciasID'><?php echo $transferencia->referencia;?></a></td>
                                                    <td><?php echo $transferencia->transaccion;?></td>
                                                    <td><?php echo $transferencia->nombreTienda;?></td>
                                                    <td><?php echo $transferencia->fechaDev;?></td>
                                                </tr>
                                    
                                    <?php   
                                        }
                                    ?>

                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                    <?php    
                        break;
                        default:
                            ?>
                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th>Fecha Giro</th>
                                            <th>Girador</th>
                                            <th>Beneficiario</th>
                                            <th>Monto</th>
                                            <th>Tasa</th>
                                            <th>Pesos</th>
                                            <th>Transferencia</th>
                                            <th>Transaccion</th>
                                            <th>Tienda</th>
                                            <th>Fecha Devolucion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($transferencias as $transferencia){
                                        ?>
                                                    <tr>
                                                        <td><?php echo $transferencia->fecha;?></td>
                                                        <td><?php echo $transferencia->girador;?></td>
                                                        <td><?php echo $transferencia->beneficiario;?></td>
                                                        <td><?php echo 'Bs.S ' . number_format($transferencia->monto,2,',','.');?></td>
                                                        <td><?php echo $transferencia->tasa;?></td>
                                                        <td><?php echo '$ ' . number_format($transferencia->pesos,2,',','.');?></td>
                                                        <td><a href="Transferencias/infoCompleta?transferencia=<?php echo $transferencia->id;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>&tienda=<?php echo $tiendaSeleccionada;?>" id='transferenciasID'><?php echo $transferencia->referencia;?></a></td>
                                                        <td><?php echo $transferencia->transaccion;?></td>
                                                        <td><?php echo $transferencia->nombreTienda;?></td>
                                                        <td><?php echo $transferencia->fechaDev;?></td>
                                                    </tr>
                                        <?php   
                                            }
                                        ?>
    
                                    </tbody>
                                </table>
                                <!-- /.table-responsive -->
                            </div>
                    <?php        
                            break;
                }
            ?>
            
            
            
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>