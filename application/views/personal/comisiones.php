<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Comisiones</h1>
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
                <form action="usuarios/comisiones" method="get" class="form-inline row">
                    <div class="form-group col-lg-offset-3 col-lg-3 ">
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
                    <?php if($this->session->userdata('tipo') != USUARIO_GIRADOR && $this->session->userdata('tipo') != USUARIO_TIENDA){ ?>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Girador</th>
                                <th>Total Transferido</th>
                                <th>Comisiones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                

                                
                                foreach($giradores as $girador){
                                    if($girador['total'] > 0){
                                        if ($i%2!=0){
                            ?>
                                            <tr class="odd">
                            <?php       }else{?>            
                                            <tr class="even">
                            <?php       }?>
                                                <td><?php echo $girador['fecha'];?></td>
                                                <td><?php echo $girador['girador'];?></td>
                                                <td><?php echo 'Bs.S ' . number_format($girador['total'],2,',','.');?></td>
                                                <td><?php echo 'Bs.S ' . number_format($girador['comision'],2,',','.');?></td>
                                            </tr>
                            <?php   
                                        $i =+1;
                                }}?>
                                    <tr class="even">
                                        <td><strong>Total</strong></td>
                                        <td></td>
                                        <td><strong><?php echo 'Bs.S ' . number_format($giradores['totalMonto'],2,',','.');?></strong></td>
                                        <td><strong><?php echo 'Bs.S ' . number_format($giradores['totalComisiones'],2,',','.');?></strong></td>
                                    </tr>

                        </tbody>
                    <?php }elseif($this->session->userdata('tipo') == USUARIO_GIRADOR){?>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cuenta</th>
                                <th>Titular</th>
                                <th>Total Transferido</th>
                                <th>Comisiones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                    foreach($cuentas as $cuenta){
                                        if($cuenta['total'] > 0){
                                        if ($i%2!=0){
                            ?>
                                            <tr class="odd">
                            <?php       }else{?>            
                                            <tr class="even">
                            <?php       }?>
                                                <td><?php echo $cuenta['fecha'];?></td>
                                                <td><?php echo $cuenta['cuenta'];?></td>
                                                <td><?php echo $cuenta['titular'];?></td>
                                                <td><?php echo 'Bs.S ' . number_format($cuenta['total'],2,',','.');?></td>
                                                <td><?php echo 'Bs.S ' . number_format($cuenta['comision'],2,',','.');?></td>
                                            </tr>
                            <?php   
                                        $i =+1;
                                    }}?>
                                    <tr class="even">
                                        <td><strong>Total</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td><strong><?php echo 'Bs.S ' . number_format($cuentas['totalMonto'],2,',','.');?></strong></td>
                                        <td><strong><?php echo 'Bs.S ' . number_format($cuentas['totalComisiones'],2,',','.');?></strong></td>
                                    </tr>

                        </tbody>
                    <?php }else{?>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Transacciones Registradas</th>
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
                                    <td><?php echo $transaccion['fecha'];?></td>
                                    <td><?php echo $transaccion['count'];?></td>
                                    <td><?php echo '$ ' . number_format($transaccion['total'],2,',','.');?></td>
                                    <td><?php echo '$ ' . number_format($transaccion['comision'],2,',','.');?></td>
                                    <td><?php echo '$ ' . number_format($transaccion['total']-$transaccion['comision'],2,',','.');?></td>
                                </tr>
                                
                            <?php $i++; }}?>
                                <tr class="even">
                                    <td><strong>Total</strong></td>
                                    <td><strong><?php echo $transacciones['resumenCount'];?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($transacciones['resumenTotal'],2,',','.');?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($transacciones['resumenComisiones'],2,',','.');?></strong></td>
                                    <td><strong><?php echo '$ ' . number_format($transacciones['resumenTotal']-$transacciones['resumenComisiones'],2,',','.');?></strong></td>
                                </tr>
                        </tbody>
                    <?php }?>
                </table>
                <?php if(isset($boton) && $boton){?>
                    <a href="usuarios/comisiones" class="btn btn-default pull-right">Volver</a>
                <?php }?>
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