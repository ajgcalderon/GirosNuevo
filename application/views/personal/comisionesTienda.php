<div class="row">
    <div class="col-lg-12">
<h1 class="page-header"><?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>Comisiones<?php }else{ ?>Reporte de Ventas<?php }?></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Resumen
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form action="usuarios/comisionesTiendas" method="get" class="form-inline row">
                <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                    <div class="form-group col-lg-offset-1 col-lg-3 ">
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
                                <?php } ?>
                    <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                        <div class="form-group col-lg-3 ">
                    <?php }else{?>
                        <div class="form-group col-lg-offset-3 col-lg-3 ">
                    <?php } ?>
                            <div class="input-group">
                            <label for="desde">Desde</label>
                                <input type="date" name="desde" class="form-control">
                                <!-- <span class="input-group-btn">
                                    <button type="submit" id="busqueda" class="btn btn-default">
                                        <i class="fa fa-search fafw"></i>
                                    </button>
                                    <input type="button" value="<i class='fa fa-search fafw'></i>" class="btn btn-default" id="busqueda">
                                </span> -->
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
                    <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){ ?>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tienda</th>
                                <th>Total Transacciones $COP</th>
                                <th>Comisiones</th>
                                <th>Retirar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                            foreach($tiendas as $tienda){
                                if($tienda['total'] > 0) {
                                if ($i%2!=0){
                            ?>
                                    <tr class="odd">
                            <?php   }else{?>            
                                    <tr class="even">
                            <?php       }?>
                                    <td><?php if($desde==$hasta){echo $desde;}else{echo $desde . ' - ' . $hasta;}?></td>
                                    <td><a href="transacciones/procesadas?tienda=<?php echo $tienda['id'];?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>&tiendaSeleccionada=<?php echo $tiendaSeleccionada;?>"><?php echo $tienda['tienda'] ?></a></td>
                                    <td><?php echo '$ ' . number_format($tienda['total'],2,',','.');?></td>
                                    <td><?php echo '$ ' . number_format($tienda['comision'],2,',','.');?></td>
                                    <td><?php echo '$ ' . number_format($tienda['total']-$tienda['comision'],2,',','.');?></td>
                                </tr>
                                
                            <?php $i++; }}?>
                                <tr class="even">
                                    <td><strong>Total</strong></td>
                                    <td>&nbsp;</td>
                                    <td><strong><?php echo '$ ' . number_format($tiendas['totalMonto'],2,',','.');?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($tiendas['totalComisiones'],2,',','.');?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($tiendas['totalMonto']-$tiendas['totalComisiones'],2,',','.');?></strong></td>
                                </tr>
                        </tbody>
                            <?php }else{?>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Numero de Transacciones</th>
                                <th>Total Transacciones $COP</th>
                                <th>Comisiones</th>
                                <th>Retirar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                            foreach($transacciones as $transaccion){
                                if($transaccion['total'] > 0) {
                                if ($i%2!=0){
                            ?>
                                    <tr class="odd">
                            <?php   }else{?>            
                                    <tr class="even">
                            <?php       }?>
                                    <td><a href="transacciones/procesadas?tienda=<?php echo $this->session->userdata('id');?>&desde=<?php echo $transaccion['fecha'];?>&hasta=<?php echo $transaccion['fecha'];?>"><?php echo $transaccion['fecha'];?></a></td>
                                    <td><?php echo $transaccion['count'];?></td>
                                    <td><?php echo '$ ' . number_format($transaccion['total'],2,',','.');?></td>
                                    <td><?php echo '$ ' . number_format($transaccion['comision'],2,',','.');?></td>
                                    <td><?php echo '$ ' . number_format($transaccion['total']-$transaccion['comision'],2,',','.');?></td>
                                </tr>
                                
                            <?php $i++; }}?>
                                <tr class="even">
                                    <td><a href="transacciones/procesadas?tienda=<?php echo $this->session->userdata('id');?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>"><strong>Total</strong></a></td>
                                    <td><strong><?php echo $transacciones['resumenCount'];?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($transacciones['resumenTotal'],2,',','.');?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($transacciones['resumenComisiones'],2,',','.');?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($transacciones['resumenTotal']-$transacciones['resumenComisiones'],2,',','.');?></strong></td>
                                </tr>
                        </tbody>
                    <?php }?>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
                <?php if(isset($boton) && $boton){?>
                    <a href="usuarios/comisionesTiendas" class="btn btn-default pull-right"><span class="fa fa-fw fa-reply"></span> Volver</a>
                <?php }?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div>
    
</div>