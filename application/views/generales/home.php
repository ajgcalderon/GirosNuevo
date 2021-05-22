<div id="Generales-Home">

    <div class="row" id="">
        <div class="col-lg-12">
            <h1 class="page-header">Estadísticas Generales</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <!-- <i class="fa fa-check-square-o fa-5x"></i> -->
                            
                            <i style="font-size: 3.8em;"class=""><?php if($this->session->userdata('tipo') != USUARIO_TIENDA && $this->session->userdata('tipo') != USUARIO_DISTRIBUIDOR && $this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR){?>Bs<?php }else{?> $<?php }?></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge" style="font-size: 2.84em;">
                                <?php
                                    echo $transferidoHoy;
                                    // if($transferidoHoy > 999999.99){
                                    //     $monto = $transferidoHoy/1000000;
                                    //     $monto = number_format($monto,2);
                                    //     echo $monto . 'M';
                                    // }elseif ($transferidoHoy > 9999.99) {
                                    //     $monto = $transferidoHoy/1000;
                                    //     $monto = number_format($monto,2);
                                    //     echo $monto . 'K';
                                    // }else {
                                    //     echo $transferidoHoy;
                                    // }
                                ?></div>
                            <!-- <div class="huge">20</div> -->
                            <div>Transferido Hoy</div>
                        </div>
                    </div>
                </div>
                <a href="Transferencias">
                    <div class="panel-footer">
                        <span class="pull-left">Ver Transferencias</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-list-alt fa-5x"></i>
                            
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $transaccionesCount;?></div>
                            <div>Giros Pendientes</div>
                            
                        </div>
                    </div>
                </div>
                <a href="transacciones/pendientes">
                    <div class="panel-footer">
                        <span class="pull-left">Ver Giros</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-warning fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $transferenciasDevueltas;?></div>
                            <!-- <div class="huge">150</div> -->
                            <div>Devoluciones</div>
                        </div>
                    </div>
                </div>
                <a href="transferencias/devoluciones">
                    <div class="panel-footer">
                        <span class="pull-left">Ver Devoluciones</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-support fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $reclamos;?></div>
                            <!-- <div class="huge">150</div> -->
                            <div>Reclamos</div>
                        </div>
                    </div>
                </div>
                <a href="reclamos">
                    <div class="panel-footer">
                        <span class="pull-left">Ver Reclamos</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        
        <?php if ($this->session->userdata('tipo') == USUARIO_GIRADOR || $this->session->userdata('tipo') == USUARIO_SUPERVISOR || ($this->session->userdata('tipo') == USUARIO_TIENDA && $this->session->userdata('grupo') == GRUPO_EMPRESA)) { ?>
            <div class="col-lg-12">
        <?php }else {?>
            <div class="col-lg-8">
        <?php }?>
            <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?>
        <!-- <div class="col-lg-8"> -->
                
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <i class="fa fa-tasks fa-fw"></i> Recargas en Espera de Confirmación
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tienda</th>
                                        <th class="center">Pesos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recargas as $recarga) {?>
                                        <tr>
                                            <th class="center"><?php echo $recarga->fecha;?></th>
                                            <th><?php echo $recarga->nombre . ' ' . $recarga->apellido;?></th>
                                            <th class="center"><?php echo '$ ' . number_format($recarga->monto,2,',','.');?></th>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <!-- /.table-responsive -->
                    </div>
                    <div class="panel-footer">
                        <a href="Recargas?estado=<?php echo ESTADO_RECARGA_ESPERA;?>" class="text-warning">Ir al Listado de Recargas <i class="fa fa-arrow-circle-right fa-fw"></i></a>           
                    </div>
                    <!-- /.panel-body -->
                </div>
            <!-- </div>     -->
            <?php
                }
            ?>
        <!-- Giros Pendientes por Banco -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-tasks fa-fw"></i> Resumen de Giros Pendientes por Banco
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Banco</th>
                                    <th class="center">Giros Pendientes</th>
                                    <th class="center">Pesos</th>
                                    <th>Monto Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bancos as $key => $value) {?>
                                    <tr>
                                        <th><?php echo $key?></th>
                                        <th class="center"><?php echo $value['conteo'];?></th>
                                        <th class="center"><?php echo '$ ' . number_format($value['pesos'],2,',','.');?></th>
                                        <th><?php echo 'Bs.S ' . number_format($value['monto'],2,',','.');?></th>
                                    </tr>
                                <?php }?>
                                
                                <tr>
                                    <th>TOTAL</th>
                                    <th class="center"><?php echo $totalConteo;?></th>
                                    <th class="center"><?php echo '$ ' . number_format($totalPesosPendientes,2,',','.');?></th>
                                    <th><?php echo'Bs.S ' . number_format($totalBsPendientes,2,',','.');?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
        <!-- /.col-lg-8 -->
        <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR || ($this->session->userdata('tipo') == USUARIO_TIENDA && $this->session->userdata('grupo') != GRUPO_EMPRESA)){
        ?>
        <!-- Saldo de Tiendas, Distribuidores y Subdistribuidores -->
        <div class="col-lg-4">
            <div class="panel panel-default">
                <?php 
                if($this->session->userdata('tipo') == USUARIO_TIENDA || $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR) {
                    if($this->session->userdata('tipo') == USUARIO_TIENDA){
                        ?>
                        <div class="panel-heading">
                            <i class="fa fa-group fa-fw"></i> Saldo Disponible
                        </div>
                <?php  }else{ ?>
                            <div class="panel-heading">
                                <i class="fa fa-group fa-fw"></i> Resumen Saldos de tiendas
                            </div>
                    <?php
                }
                    ?>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="list-group">
                        <?php
                            
                            foreach ($saldoTiendas as $key => $value) {
                                if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR){                           
                                ?>
                                <a href="Usuarios/listadoDeCuentasTiendas?tienda=<?php echo $value['id'];?>" class="list-group-item">
                                    <i class="fa fa-bank fa-fw"></i> <?php echo $value['nombre'];?>
                                    <span class="pull-right text-muted small"><em><?php echo '$ ' . number_format($value['saldo'],2,',','.');?></em>
                                    </span>
                                </a>
                            <?php }elseif($this->session->userdata('tipo') == USUARIO_TIENDA){ ?>
                                <a href="Usuarios/listadoDeCuentasTiendas?tienda=<?php echo $this->session->userdata('id');?>" class="list-group-item">
                                    <i class="fa fa-bank fa-fw"></i> <?php echo $value['nombre'];?>
                                    <span class="pull-right text-muted small"><em><?php echo '$ ' . number_format($value['saldo'],2,',','.');?></em>
                                    </span>
                                </a>
                            <?php }?>
                        <?php
                        }
                        ?>
                            
                    </div>
                    <!-- /.list-group -->
                    <?php
                        if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR){ ?>
                            <a href="usuarios/listadoDeCuentasTiendas" class="btn btn-default btn-block">Ver Saldos Disponibles</a>
                        <?php }else{ ?>
                            <a href="usuarios/listadoDeCuentasTiendas" class="btn btn-default btn-block">Ver Saldo Disponible</a>
                        <?php } ?>
                </div>
                <!-- /.panel-body -->
                <?php
                    }
                    ?>
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-4 -->
        
        <!-- Recargas Pendientes -->
        
        
        <?php
            }
            
        ?>
    </div>
    
</div>
<?php if(isset($mensajes)){ 
    $i=1;
    foreach($mensajes as $mensaje){ 
        if((isset($mensaje->hasta) && date('Y-m-d')<= $mensaje->hasta) || !$mensaje->hasta){
        ?>

        <div class="modal fade" id="miModal<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <center><h4 class="modal-title text-warning" id="myModalLabel"><?php echo $mensaje->titulo;?></h4></center>
                    </div>
                    <div class="modal-body">
                        <p><?php echo $mensaje->mensaje;?></p>
                    </div>
                </div>
            </div>
        </div>
<?php }
    $i++;
}} ?>