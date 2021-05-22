<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Recargas</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de Recargas
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form action="Recargas" method="get" class="form-inline row">
                <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                    <div class="form-group col-lg-offset-1 col-lg-3 ">
                        <div class="input-group">
                            <label for="tienda">Tienda</label>
                            <select name="tienda" id="tienda" class="form-control">
                                <option value="">Seleccione una Tienda</option>
                                <?php foreach ($listaTiendas as $tienda) { ?>
                                    <option value="<?php echo $tienda->id;?>"><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
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
                            <!-- <span class="input-group-btn">
                                <button type="submit" id="busqueda" class="btn btn-default">
                                    <i class="fa fa-search fafw"></i>
                                </button>
                                 <input type="button" value="<i class='fa fa-search fafw'></i>" class="btn btn-default" id="busqueda"> 
                            </span> -->
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><span class="fa fafw fa-search"></span></button>
                </form>
                <br><br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Referencia</th>
                                <th>Tienda</th>
                                <th>Monto $COP</th>
                                <th>Comision</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Verficado por</th>
                                <th>Reporte</th>
                                <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?><th>Opciones</th><?php }?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                            foreach($recargas as $recarga){
                                ?>           
                                <tr>
                                    <td><?php echo $recarga->fecha;?></td>
                                    <td><?php if($recarga->referencia != 'Diferido'){?><a href="Recargas/detalles?id=<?php echo $recarga->id;?>"><?php echo $recarga->referencia;?></a><?php }else{echo $recarga->referencia;}?></td>
                                    <td><?php echo $recarga->nombre . ' ' . $recarga->apellido;?></td>
                                    <td <?php if($recarga->estado != ESTADO_RECARGA_RECHAZADA && $recarga->referencia != 'Diferido'){?> class="text-success"<?php }else{?>class="text-danger"<?php }?>><?php if($recarga->estado != ESTADO_RECARGA_RECHAZADA && $recarga->referencia != 'Diferido'){ echo '$ '; }else{ echo '$ '; }  echo number_format($recarga->monto,2,',','.');?></td>
                                    <td <?php if($recarga->estado != ESTADO_RECARGA_RECHAZADA && $recarga->referencia != 'Diferido'){?> class="text-success"<?php }else{?>class="text-danger"<?php }?>><?php if($recarga->estado != ESTADO_RECARGA_RECHAZADA && $recarga->referencia != 'Diferido'){ echo '$ '; }else{ echo '$ '; }  echo number_format($recarga->monto*$recarga->comision/100,2,',','.'); $total['comision'] += $recarga->monto*$recarga->comision/100;?></td>
                                    <td <?php if($recarga->estado != ESTADO_RECARGA_RECHAZADA && $recarga->referencia != 'Diferido'){?> class="text-success"<?php }else{?>class="text-danger"<?php }?>><?php if($recarga->estado != ESTADO_RECARGA_RECHAZADA && $recarga->referencia != 'Diferido'){ echo '$ '; }else{ echo '$ '; }  echo number_format($recarga->monto+$recarga->monto*$recarga->comision/100,2,',','.');?></td>
                                    <td><?php if($recarga->referencia != 'Diferido'){echo $recarga->estado;}else{ echo 'N/A';}?></td>
                                    <td><?php echo $recarga->nombreAdministrador;?></td>
                                    <td><?php if($recarga->referencia != 'Diferido'){?><a href="<?php echo $recarga->comprobante;?>" class="btn btn-primary btn-small" target="_blank" rel="noopener noreferrer"><i class="fa fa-fw fa-eye"></i></a><?php }?></td>
                                    <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?><td><?php if($recarga->estado == ESTADO_RECARGA_ESPERA){?><a href="Recargas/procesar?id=<?php echo $recarga->id;?>&estado=<?php echo ESTADO_RECARGA_ACEPTADA;?>" class="btn btn-primary">Aceptar</a><a href="Recargas/procesar?id=<?php echo $recarga->id;?>&estado=<?php echo ESTADO_RECARGA_RECHAZADA;?>" class="btn btn-danger">Rechazar</a><?php }?></td><?php }?>
                                </tr>
                                
                            <?php 
                                
                            }
                            foreach ($transacciones as $transaccion ) {
                            ?>
                                <tr>
                                    <td><?php echo $transaccion->fecha;?></td>
                                    <td><?php echo 'Giro: ' . $transaccion->referencia;?></td>
                                    <td><?php echo $transaccion->nombreTienda . ' ' . $transaccion->apellidoTienda; ?></td>
                                    <td class="text-success"><?php echo '$ ' . number_format($transaccion->pesos,2,',','.');?></td>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                    <td class="text-danger"><?php echo $transaccion->estado;?></td>
                                    <td>N/A</td>
                                    <td><a href="<?php if($transaccion->estado == 'Devolucion'){?>Transferencias/infoCompleta?transferencia=<?php echo $transaccion->transferencia;}else{ ?>Transacciones/detalles?transaccion=<?php echo $transaccion->referencia; }?>" class="btn btn-primary btn-small" target="_blank" rel="noopener noreferrer"><i class="fa fa-fw fa-eye"></i></a></td>
                                    <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?><td></td><?php }?>
                                </tr>    
                            <?php 
                            }
                            ?>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong><?php echo '$ ' . number_format($total['pesos'],2,',','.');?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($total['comision'],2,',','.');?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($total['pesos']+$total['comision'],2,',','.');?></strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?><td></td><?php }?>
                                </tr>
                        </tbody>
                </table>
                <?php if(isset($boton) && $boton){?>
                    <a href="usuarios/comisionesTiendas" class="btn btn-default pull-right">Volver</a>
                <?php }?>
                <!-- /.table-responsive -->
                <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                    <a href="Recargas/export?tienda=<?php echo $tiendaSeleccionada;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>&estado=<?php echo $estado;?>" target="_blank" class="btn btn-success"><i class="fa fafw fa-file-excel-o"></i> Exportar a Excel</a>
                <?php }else{?>
                    <a href="Recargas/export?tienda=<?php echo $this->session->userdata('id');?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>&estado=<?php echo $estado;?>" target="_blank" class="btn btn-success"><i class="fa fafw fa-file-excel-o"></i> Exportar a Excel</a>
        
                <?php }?>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div>
    
</div>