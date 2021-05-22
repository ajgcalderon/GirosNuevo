<div class="row">
    <div class="col-lg-12">
        <center>
            <h2 class="page-header text-dark"><?php echo $nombreTienda;?></h2>
        </center>
    </div>
</div>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <!-- <form action="transacciones/busqueda" method="get">
            <div class="form-group">
                <div class="input-group custom-search-form form-inline">
                    <input type="text" name="busqueda" id="busqueda" class="form-control" placeholder="Buscar...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </div>
        </form> -->
    </div>
    <div class="col-lg-3"></div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                Transacciones Procesadas
            </div>
            
            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php if ($this->session->userdata('tipo') == 3) { ?>
                    <form action="Transacciones/cancelar" method="post">
                
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Cedula</th>
                            <th>Beneficiario</th>
                            <th>Banco</th>
                            <th>Nº Cuenta</th>
                            <th>Monto</th>
                            <th>Tasa</th>
                            <th>Pesos</th>
                            <th>Comision</th>
                            <th>Retirar</th>
                            <th>Tienda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 3;
                            foreach($transacciones as $transaccion){ 
                                if ($i%2!=0){ ?>
                                    <tr class="odd">
                        <?php   }else{?>
                                    <tr class="even">
                        <?php   }?>
                        <td><?php if($transaccion->estado == ESTADO_TRANSACCION_PENDIENTE){?><input type="checkbox" name="transacciones[]" id="" value="<?php echo $transaccion->referencia;?>"><?PHP }?></td>
                                        <td><?php echo $transaccion->fecha;?></td>
                                        <td><a href="Transacciones/detallesProcesadas?transaccion=<?php echo $transaccion->referencia;?>"><?php echo $transaccion->referencia;?></a></td>
                                        <td><?php echo $transaccion->cedula;?></td>
                                        <td><?php echo $transaccion->beneficiario;?></td>
                                        <td><?php echo $transaccion->banco;?></td>
                                        <td><?php echo substr($transaccion->cuenta,0,4) . '-' . substr($transaccion->cuenta,4,4) . '-' . substr($transaccion->cuenta,8,4) . '-' . substr($transaccion->cuenta,12,4) . '-' . substr($transaccion->cuenta,16,4);?></td>
                                        <td><?php echo 'Bs.S ' . number_format($transaccion->monto,2,',','.');?></td>
                                        <td><?php echo $transaccion->tasa;?></td>
                                        <td><?php echo '$ ' . number_format($transaccion->pesos,2,',','.');?></td>
                                        <td><?php echo '$ ' . number_format($transaccion->pesos*$transaccion->comision/100,2,',','.'); ?></td>
                                        <td><?php echo '$ ' . number_format($transaccion->pesos-($transaccion->pesos*$transaccion->comision/100),2,',','.');?></td>
                                        <td><?php echo $transaccion->nombreTienda . ' ' . $transaccion->apellidoTienda;?></td>
                                    </tr>
                        <?php
                                $i =+1;
                            }
                            ?>
                            <tr class="even">
                                <td><strong>Totales</strong></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><?php echo 'Bs.S ' . number_format($totalBolivares,2,',','.');?></td>
                                <td>&nbsp;</td>
                                <td><?php echo '$ ' . number_format($totalPesos,2,',','.');?></td>
                                <td><?php echo '$ ' . number_format($totalComision,2,',','.'); ?></td>
                                <td><strong><?php echo '$ ' . number_format($totalPesos-$totalComision,2,',','.');?></strong></td>
                                <td>&nbsp;</td>
                            </tr>

                    </tbody>
                </table>
                            <center><button type="submit" class="btn btn-primary">Cancelar</button></center>
                    </form>
                <?php }else{?>
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Cedula</th>
                            <th>Beneficiario</th>
                            <th>Banco</th>
                            <th>Nº Cuenta</th>
                            <th>Monto</th>
                            <th>Tasa</th>
                            <th>Pesos</th>
                            <th>Comision</th>
                            <th>Retirar</th>
                    </thead>
                    <tbody>
                        <?php
                            $i = 3;
                            foreach($transacciones as $transaccion){ 
                                if ($i%2!=0){
                        ?>
                                    <tr class="odd">
                        <?php   }else{?>
                                    <tr class="even">
                        <?php  }?>
                                        <td><?php echo $transaccion->fecha;?></td>
                                        <td><a href="Transacciones/detallesProcesadas?transaccion=<?php echo $transaccion->referencia;?>&tienda=<?php echo $tienda;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>"><?php echo $transaccion->referencia;?></a></td>
                                        <td><?php echo $transaccion->cedula;?></td>
                                        <td><?php echo $transaccion->beneficiario;?></td>
                                        <td><?php echo $transaccion->banco;?></td>
                                        <td><?php echo substr($transaccion->cuenta,0,4) . '-' . substr($transaccion->cuenta,4,4) . '-' . substr($transaccion->cuenta,8,4) . '-' . substr($transaccion->cuenta,12,4) . '-' . substr($transaccion->cuenta,16,4);?></td>
                                        <td><?php echo 'Bs.S ' . number_format($transaccion->monto,2,',','.');?></td>
                                        <td><?php echo $transaccion->tasa;?></td>
                                        <td><?php echo '$ ' . number_format($transaccion->pesos,2,',','.');?></td>
                                        <td><?php echo '$ ' . number_format($transaccion->pesos*$transaccion->comision/100,2,',','.'); ?></td>
                                        <td><?php echo '$ ' . number_format($transaccion->pesos-($transaccion->pesos*$transaccion->comision/100),2,',','.');?></td>
                                        
                                    </tr>
                        <?php   $i =+1;
                            }
                        ?>
                        <tr class="even">
                                <td><strong>Totales</strong></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><strong><?php echo 'Bs.S ' . number_format($totalBolivares,2,',','.');?></strong></td>
                                <td>&nbsp;</td>
                                <td><strong><?php echo '$ ' . number_format($totalPesos,2,',','.');?></strong></td>
                                <td><strong><?php echo '$ ' . number_format($totalComision,2,',','.'); ?></strong></td>
                                <td><strong><?php echo '$ ' . number_format($totalPesos-$totalComision,2,',','.');?></strong></td>
                            </tr>
                    </tbody>
                </table>
                        <?php }?>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
            <a href="usuarios/comisionesTiendas?tienda=<?php echo $tiendaSeleccionada;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>" class="btn btn-default pull-right"><span class="fa fa-fw fa-reply"></span> Volver</a>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div>
</div>