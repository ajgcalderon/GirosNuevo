<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header text-dark">Transacciones</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        
    </div>
    <div class="col-lg-3"></div>
</div>
<div class="row">
    <div class="col-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                Transacciones Pendientes
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <!-- <form action="Transacciones/filtro" method="get" id="filtrarTransacciones" class="form-inline col-lg-12">
                        <?php #if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                            <div class="form-group col-lg-3">
                                <div class="input-group">
                                    <label for="tienda">Tienda</label>
                                    <select name="tienda" id="tienda" class="form-control">
                                        <option value="">Seleccione una Tienda</option>
                                        <?php #foreach ($tiendas as $tienda) { ?>
                                            <option value="<?php #echo $tienda->id;?>"><?php #echo $tienda->nombre . ' ' . $tienda->apellido;?></option>
                                        <?php# }?>
                                    </select>
                                </div>
                            </div>
                        <?php #} ?>
                        <?php# if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                            <div class="form-group col-lg-2 ">
                        <?php #}else{?>
                            <div class="form-group col-lg-offset-2 col-lg-2 ">
                        <?php #} ?>
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
                                        <option value="<?php #echo ESTADO_TRANSACCION_PENDIENTE;?>"><?php echo ESTADO_TRANSACCION_PENDIENTE;?></option>
                                        <option value="<?php# echo ESTADO_TRANSACCION_PROCESADO;?>"><?php echo ESTADO_TRANSACCION_PROCESADO;?></option>
                                        <option value="<?php #echo ESTADO_TRANSACCION_VERIFICACION;?>"><?php echo ESTADO_TRANSACCION_VERIFICACION;?></option>
                                        <option value="<?php #echo ESTADO_TRANSACCION_CANCELADO;?>"><?php echo ESTADO_TRANSACCION_CANCELADO;?></option>
                                    </select>
                                </div>
                            </div>
                        <button type="submit" class="btn btn-primary"><span class="fa fafw fa-search"></span></button>
                    </form> -->
                </div>
                <br>
                <?php if ($this->session->userdata('tipo') == USUARIO_GIRADOR) { ?><form action="Transacciones/cancelar" method="post"><?php }?>
                
                    <table width="100%" class="table table-striped table-bordered table-hover dataTables">
                    <thead>
                        <tr>
                            <?php if ($this->session->userdata('tipo') == USUARIO_GIRADOR) { ?><th></th><?php }?>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Cedula</th>
                            <th>Beneficiario</th>
                            <th>Banco</th>
                            <th>Nº Cuenta</th>
                            <th>Monto</th>
                            <th>Tasa</th>
                            <th>Pesos</th>
                            <th>Estado</th>
                            <?php if($this->session->userdata('tipo') != USUARIO_SUPERVISOR){?><th>Tienda</th><?php }?>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                            foreach($transacciones as $transaccion){ 
                                ?>
                                    <tr>
                       
                                        <?php if($this->session->userdata('tipo') == USUARIO_GIRADOR && $transaccion->estado == ESTADO_TRANSACCION_PENDIENTE){?><td><input type="checkbox" name="transacciones[]" id="" value="<?php echo $transaccion->referencia;?>"></td><?php }?>
                                        <td><?php echo $transaccion->fecha;?></td>
                                        <td><a href="Transacciones/detalles?transaccion=<?php echo $transaccion->referencia;?>"><?php echo $transaccion->referencia;?></a></td>
                                        <td><?php echo $transaccion->cedula;?></td>
                                        <td><?php echo $transaccion->beneficiario;?></td>
                                        <td><?php echo $transaccion->banco;?></td>
                                        <td><?php echo substr($transaccion->cuenta,0,4) . '-' . substr($transaccion->cuenta,4,4) . '-' . substr($transaccion->cuenta,8,4) . '-' . substr($transaccion->cuenta,12,4) . '-' . substr($transaccion->cuenta,16,4);?></td>
                                        <td><?php echo 'Bs.S ' . number_format($transaccion->monto,2,',','.');?></td>
                                        <td><?php echo $transaccion->tasa;?></td>
                                        <td><?php echo '$ ' . number_format($transaccion->pesos,2,',','.');?></td>
                                        <td><?php echo $transaccion->estado; if($transaccion->girador){echo " (Asignada)";}?></td>
                                        <td><?php echo $transaccion->nombreTienda . ' ' . $transaccion->apellidoTienda;?></td>
                                        <!-- Botonoes de banco y previsualizacion de transaccion -->
                                        <!-- <td><button class="btn btn-default btn-small bank-btn" transaccion="<?php echo $transaccion->referencia;?>"><i class="fa fa-bank fa-fw"></i></button></td>
                                        <td><button class="btn btn-default btn-small preview-btn" transaccion="<?php echo $transaccion->referencia;?>"><i class="fa fa-file fa-fw"></i></button></td> -->
                                    </tr>
                        <?php
                            }
                        ?>
                        <tr>
                            <td><strong>Total</strong></td>
                            <?php if ($this->session->userdata('tipo') == USUARIO_GIRADOR) { ?><td></td><?php }?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong><?php echo $total['bs'];?></strong></td>
                            <td></td>
                            <td><strong><?php echo $total['pesos'];?></strong></td>
                            <td></td>
                            <?php if($this->session->userdata('tipo') != USUARIO_SUPERVISOR){?><td></td><?php }?>
                            <!-- Botonoes de banco y previsualizacion de transaccion -->
                            <!-- <td></td>
                            <td></td> -->
                        </tr>
                    </tbody>
                </table>
                <?php if ($this->session->userdata('tipo') == USUARIO_GIRADOR) { ?><center><button type="submit" class="btn btn-primary">Cancelar</button></center>
                    </form><?php }?>
                
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
<div class="modal fade" id="TransaccionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <center><h4 class="modal-title text-warning" id="myModalLabel"><?php echo $mensaje->titulo;?></h4></center>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>