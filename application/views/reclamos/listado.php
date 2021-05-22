<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Reclamos</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <form action="transacciones/busquedaReclamos" method="get">
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
        </form>
    </div>
    <div class="col-lg-3"></div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-red">
            <div class="panel-heading text-danger">
                Reclamos Actuales
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <form action="Reclamos" method="get" class="form-inline col-lg-12">
                        <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                            <div class="form-group col-lg-offset-2 col-lg-3 ">
                                <div class="input-group">
                                    <label for="tienda">Tienda</label>
                                    <select name="tienda" id="tienda" class="form-control">
                                        <option value="">Seleccione una Tienda</option>
                                        <?php foreach ($listaTiendas as $tienda) { ?>
                                            <option value="<?php echo $tienda->id;?>" <?php if($tiendaSeleccionada == $tienda->id){echo "Selected";}?>><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-offset-1 col-lg-3 ">
                        <?php }else{ ?>
                            <div class="form-group col-lg-offset-4 col-lg-3 ">
                        <?php } ?>
                                <div class="input-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="<?php echo RECLAMO_ESTADO_INICIADO; ?>" <?php if($estado == RECLAMO_ESTADO_INICIADO){echo "selected";}?>>Reclamos Pendientes</option>
                                        <option value="<?php echo RECLAMO_ESTADO_RESUELTO; ?>" <?php if($estado == RECLAMO_ESTADO_RESUELTO){echo "selected";}?>>Reclamos Resueltos</option>
                                    </select>
                                </div>
                            </div>
                    <button type="submit" class="btn btn-primary"><span class="fa fafw fa-search"></span></button>
                
                    </form>
                </div>
                <br>
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
                            <th>$Col</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 3;
                            foreach($reclamos as $reclamo){
                                if ($i%2!=0){
                        ?>
                                    <tr class="odd">
                                        <td><?php echo $reclamo->fecha;?></td>
                                        <td><a href="Reclamos/detalles?transaccion=<?php echo $reclamo->referencia;?>&estado=<?php echo $estado;?>&tienda=<?php echo $tiendaSeleccionada;?>"><?php ?><?php echo $reclamo->referencia;?></a></td>
                                        <td><?php echo $reclamo->cedula;?></td>
                                        <td><?php echo $reclamo->beneficiario;?></td>
                                        <td><?php echo $reclamo->banco;?></td>
                                        <td><?php echo $reclamo->cuenta;?></td>
                                        <td><?php echo 'Bs.S ' . number_format($reclamo->monto,2,',','.');?></td>
                                        <td><?php echo $reclamo->tasa;?></td>
                                        <td><?php echo $reclamo->pesos;?></td>
                                    </tr>
                        
                        <?php   }else{?>
                                    <tr class="even">
                                        <td><?php echo $reclamo->fecha;?></td>
                                        <td><a href="Reclamos/detalles?transaccion=<?php echo $reclamo->referencia;?>&estado=<?php echo $estado;?>&tienda=<?php echo $tiendaSeleccionada;?>"><?php ?><?php echo $reclamo->referencia;?></a></td>
                                        <td><?php echo $reclamo->cedula;?></td>
                                        <td><?php echo $reclamo->beneficiario;?></td>
                                        <td><?php echo $reclamo->banco;?></td>
                                        <td><?php echo $reclamo->cuenta;?></td>
                                        <td><?php echo 'Bs.S ' . number_format($reclamo->monto,2,',','.');?></td>
                                        <td><?php echo $reclamo->tasa;?></td>
                                        <td><?php echo $reclamo->pesos;?></td>
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