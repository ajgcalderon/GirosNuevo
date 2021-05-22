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
                Reporte General de Transferencias
            </div>
            <!-- /.panel-heading -->
            
                        <div class="panel-body">
                            <div class="row">
                                <form action="Transferencias" method="get" class="form-inline col-lg-12">
                                    <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                                        <div class="form-group col-lg-3 ">
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
                                        <div class="form-group col-lg-2 ">
                                    <?php }else{?>
                                        <div class="form-group col-lg-offset-2 col-lg-2 ">
                                    <?php } ?>
                                            <div class="input-group">
                                            <label for="desde">Desde</label>
                                                <input type="date" name="desde" class="form-control">
                                            </div>
                                        </div>
                                    <div class="form-group col-lg-2">
                                        <div class="input-group">
                                            <label for="hasta">Hasta</label>
                                            <input type="date" name="hasta" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-offset-1 col-lg-3 ">
                                            <div class="input-group">
                                                <label for="estado">Estado</label>
                                                <select name="estado" id="estado" class="form-control">
                                                    <option value="">Seleccione un Estado</option>
                                                    <option value="<?php echo ESTADO_TRANFERENCIAS_EJECUTADA;?>">Ejecutadas</option>
                                                    <option value="<?php echo ESTADO_TRANFERENCIAS_DEVUELTA;?>">Devueltas</option>
                                                </select>
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
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Nº Referencia</th>
                                        <th>Girador</th>
                                        <th>Beneficiario</th>
                                        <th>Monto</th>
                                        <th>Transaccion</th>
                                        <th>Tienda</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1;
                                        foreach($transferencias as $transferencia){
                                    ?>
                                                <tr <?php if($transferencia->estado == ESTADO_TRANFERENCIAS_DEVUELTA){?> class="danger"<?php }?>>
                                                    <td><?php echo $transferencia->fecha;?></td>
                                                    <td><a href="Transferencias/infoCompleta?transferencia=<?php echo $transferencia->idTransferencia;?>&tienda=<?php echo $tiendaSeleccionada; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta?>" id='transferenciasID'><?php echo $transferencia->referencia;?></a></td>
                                                    <td><?php echo $transferencia->girador;?></td>
                                                    <td><?php echo $transferencia->beneficiario;?></td>
                                                    <td><?php echo 'Bs.S ' . number_format($transferencia->monto,2,',','.');?></td>
                                                    <td><?php echo $transferencia->transaccion;?></td>
                                                    <td><?php echo $transferencia->nombreTienda;?></td>
                                                    <td><?php if($transferencia->estado == ESTADO_TRANFERENCIAS_EJECUTADA){echo "Procesada";}else{echo "Devuelta";};?></td>
                                                </tr>
                                    <?php   
                                        }
                                    ?>
                                            <tr class="even">
                                                <td><strong>Total</strong></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><strong><?php echo 'Bs.S ' . number_format($total['bs'],2,',','.');?></strong></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                <?php
                        break;
                    case USUARIO_GIRADOR:
                ?>
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Nº Referencia</th>
                                        <th>Beneficiario</th>
                                        <th>Monto</th>
                                        <th>Transaccion</th>
                                        <th>Tienda</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1;
                                        foreach($transferencias as $transferencia){
                                    ?>        
                                                <tr <?php if($transferencia->estado == ESTADO_TRANFERENCIAS_DEVUELTA){?> class="danger"<?php }?>>
                                                    <td><?php echo $transferencia->fecha;?></td>
                                                    <td><a href="Transferencias/infoCompleta?transferencia=<?php echo $transferencia->idTransferencia;?>&tienda=<?php echo $tiendaSeleccionada; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta?>" id='transferenciasID'><?php echo $transferencia->referencia;?></a></td>
                                                    <td><?php echo $transferencia->beneficiario;?></td>
                                                    <td><?php echo 'Bs.S ' . number_format($transferencia->monto,2,',','.');?></td>
                                                    <td><?php echo $transferencia->transaccion;?></td>
                                                    <td><?php echo $transferencia->nombreTienda;?></td>
                                                    <td><?php if($transferencia->estado == ESTADO_TRANFERENCIAS_EJECUTADA){echo "Procesada";}else{echo "Devuelta";};?></td>
                                                </tr> 
                                    <?php
                                        }
                                    ?>
                                            <tr class="even">
                                                <td><strong>Total</strong></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><strong><?php echo 'Bs.S ' . number_format($total['bs'],2,',','.');?></strong></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
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
                                                    <th>Fecha</th>
                                                    <th>Nº Referencia</th>
                                                    <th>Girador</th>
                                                    <th>Beneficiario</th>
                                                    <th>Pesos</th>
                                                    <th>Tasa</th>
                                                    <th>Monto</th>
                                                    <th>Transaccion</th>
                                                    <th>Tienda</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $i = 1;
                                                    foreach($transferencias as $transferencia){
                                                        
                                                ?>
                                                            <tr <?php if($transferencia->estado == ESTADO_TRANFERENCIAS_DEVUELTA){?> class="danger"<?php }?>>
                                                                <td><?php echo $transferencia->fecha;?></td>
                                                                <td><a href="Transferencias/infoCompleta?transferencia=<?php echo $transferencia->idTransferencia;?>&tienda=<?php echo $tiendaSeleccionada; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta?>" id='transferenciasID'><?php echo $transferencia->referencia;?></a></td>
                                                                <td><?php echo $transferencia->girador;?></td>
                                                                <td><?php echo $transferencia->beneficiario;?></td>
                                                                <?php if($this->session->userdata('tipo') == USUARIO_TIENDA){?>
                                                                    <?php if($transferencia->estado == ESTADO_TRANFERENCIAS_EJECUTADA){?>
                                                                        <td class="text-danger"><?php echo '$ -' . number_format($transferencia->pesos,2,',','.');?></td>
                                                                    <?php }else{ ?>
                                                                        <td class="text-success"><?php echo '$ +' . number_format($transferencia->pesos,2,',','.');?></td>
                                                                    <?php }?>
                                                                <?php }else{?>
                                                                    <td ><?php echo '$ ' . number_format($transferencia->pesos,2,',','.');?></td>

                                                                <?php }?>
                                                                <td><?php echo $transferencia->tasa;?></td>
                                                                <td><?php echo 'Bs.S ' . number_format($transferencia->monto,2,',','.');?></td>
                                                                <td><?php echo $transferencia->transaccion;?></td>
                                                                <td><?php echo $transferencia->nombreTienda;?></td>
                                                                <td><?php if($transferencia->estado == ESTADO_TRANFERENCIAS_EJECUTADA){echo "Procesada";}else{echo "Devuelta";};?></td>
                                                            </tr>
                                                            <?php
                                                    }
                                                    if ($transacciones) {
                                                        # code...
                                                        foreach ($transacciones as $transaccion) {
                                                            ?>
                                                            <tr class="danger">
                                                                <td><?php echo $transaccion->fecha;?></td>
                                                                <td>N/A</td>
                                                                <td><?php echo 'N/A';?></td>
                                                                <td><?php echo $transaccion->beneficiario;?></td>
                                                                <?php if($this->session->userdata('tipo') == USUARIO_TIENDA){?> 
                                                                        <td class="text-success"><?php echo '$ +' . number_format($transaccion->pesos,2,',','.');?></td>
                                                                <?php }else{?>
                                                                        <td><?php echo '$ ' . number_format($transaccion->pesos,2,',','.');?></td>
                                                                <?php }?>
                                                                <td><?php echo $transaccion->tasa;?></td>
                                                                <td><?php echo 'Bs.S ' . number_format($transaccion->monto,2,',','.');?></td>
                                                                <td><a href="Transacciones/detalles?transaccion=<?php echo $transaccion->referenciaTransaccion;?>"><?php echo $transaccion->referenciaTransaccion?></a></td>
                                                                <td><?php echo $transaccion->nombreTienda . ' ' . $transaccion->apellidoTienda;?></td>
                                                                <td class="text-danger"><?php echo $transaccion->estado?></td>
                                                            </tr>
                                                <?php
                                                        }
                                                    }   
                                                ?>
                                                        <tr class="even">
                                                            <td><strong>Total</strong></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><strong><?php echo '$ ' . number_format($total['pesos'],2,',','.');?></strong></td>
                                                            <td></td>
                                                            <td><strong><?php echo 'Bs.S ' . number_format($total['bs'],2,',','.');?></strong></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                            </tbody>
                                        </table>
                                        <!-- /.table-responsive -->
                                        <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                                            <a href="Transferencias/export?tienda=<?php echo $tiendaSeleccionada;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>&estado=<?php echo $estado;?>" target="_blank" class="btn btn-success"><i class="fa fafw fa-file-excel-o"></i> Exportar a Excel</a>
                                        <?php }else{?>
                                            <a href="Transferencias/export?tienda=<?php echo $this->session->userdata('id');?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>&estado=<?php echo $estado;?>" target="_blank" class="btn btn-success"><i class="fa fafw fa-file-excel-o"></i> Exportar a Excel</a>
                                
                                        <?php }?>
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
<?php if(isset($totales) & $totales){?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-green">
                <div class="panel-heading">
                    Total de Transferencias Giradores
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover dataTables">
                        <thead>
                            <th>Fecha</th>
                            <th>Girador</th>
                            <th>Monto</th>
                        </thead>
                        <tbody>
                            <?php foreach ($totales as $girador) {?>
                                <tr>
                                    <td><?php echo $girador['fecha'];?></td>
                                    <td><?php echo $girador['girador'];?></td>
                                    <td><?php echo '<strong>Bs.S</strong> ' . number_format($girador['totalTransferido'],2,',','.');?></td>
                                </tr>
                            <?php };?>
                            <tr>
                                <td><strong>Total: </strong></td>
                                <td></td>
                                <td><?php if ($this->session->userdata('tipo') != USUARIO_TIENDA){ echo '<strong>Bs.S</strong> ' . number_format($total['bs'],2,',','.'); }else{ echo '<strong>$</strong> ' . number_format($total['pesos'],2,',','.'); }?></td>
                            </tr>
                        </tbody>
                    
                        
                </div>
            </div>
        </div>
    </div>
<?php }?>