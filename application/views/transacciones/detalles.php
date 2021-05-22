<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Transacciones</h1>
    </div>
    <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_TIENDA  || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR){?>
    <div class="col-lg-2 pull-right row">
        <a href="Transacciones/factura?transaccion=<?php echo $transaccion->referencia;?>" target="_blank" class="btn btn-default btn-lg">
            <span class="fa fafw fa-print"></span>
        </a>
    </div>
    <?php }?>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                Información Completa de la Transaccion
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- <h2 class="text-dark">Transaccion</h2> -->
                <?php if(isset($error)){ ?>
                    <div class="alert alert-danger col-lg-4 col-lg-offset-4"><center><?php echo $error;?></center></div>
                <?php }?>
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-offset-3 col-md-offset-3 col-lg-6 col-md-6 col-sm-12 row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <strong>Fecha</strong><br>
                                <strong>N° Referencia</strong><br>
                                <strong>Monto</strong><br>
                                <strong>Tasa</strong><br>
                                <strong>Pesos</strong><br>
                                <strong>Estado</strong><br><br>
                                
                                <strong>Beneficiario</strong><br>
                                <strong>Cedula</strong><br>
                                <strong>Banco</strong><br>
                                <strong>Numero de Cuenta</strong><br><br>
                                
                                <?php if($transaccion->tienda == $this->session->userdata('id') || $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?>
                                    <strong>Nombre del Cliente</strong><br>
                                    <strong>Telefono de Contacto</strong><br><br>
                                <?php } ?>
                                <?php if($transaccion->girador && $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?>
                                    <strong>Girador</strong><br>
                                <?php }?>
                                <!-- <strong>Tienda</strong><br>
                                <strong>Girador</strong><br> -->
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <?php echo $transaccion->fecha;?><br>
                                <?php echo $transaccion->referencia;?><br>
                                <?php echo 'Bs.S ' . number_format($transaccion->monto,2,',','.');?><br>
                                <?php echo $transaccion->tasa;?><br>
                                <?php echo '$COP ' . number_format($transaccion->pesos,2,',','.');?><br>
                                <?php echo $transaccion->estado; if($transaccion->girador){echo " (Asignada)";}?><br><br>

                                <?php echo $transaccion->beneficiario;?><br>
                                <?php echo $transaccion->cedula;?><br>
                                <?php echo $transaccion->banco;?><br>
                                <?php echo substr($transaccion->cuenta,0,4) . '-' . substr($transaccion->cuenta,4,4) . '-' . substr($transaccion->cuenta,8,4) . '-' . substr($transaccion->cuenta,12,4) . '-' . substr($transaccion->cuenta,16,4);?><br><br>
                                
                                <?php if($transaccion->tienda == $this->session->userdata('id') || $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?>
                                    <?php echo $transaccion->nombreCliente;?><br>
                                    <?php echo $transaccion->telefono;?><br><br>
                                <?php }?>
                                <?php if($transaccion->girador && $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?>
                                    <?php echo $girador->nombre . ' ' . $girador->apellido;?><br>
                                <?php }?>
                            </div>
                        </div>
                        <br>
            </div>   
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="col-lg-12">
    <?php if(($transaccion->tienda == $this->session->userdata('id') || $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR) && $botones && $transaccion->estado != ESTADO_TRANSACCION_CANCELADO && $transaccion->estado != ESTADO_TRANSACCION_PROCESADO){
            ?>
        <div class="row col-lg-12">
        <?php if(($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR) && $botones){?>
            <a href="Transacciones/estadoTransaccionBoton?transaccion=<?php echo $transaccion->referencia;?>&estado=<?php echo ESTADO_TRANSACCION_VERIFICACION; ?>" class="btn btn-warning btn-outline">Espera de Correccion</a>
        <?php }?>
            <a href="Transacciones/verificacion?transaccion=<?php echo $transaccion->referencia;?>" class="btn btn-primary btn-outline">Modificar</a>
            <?php if(!$transaccion->girador || $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR && $this->session->userdata('tipo') != USUARIO_TIENDA){?><a href="Transacciones/estadoTransaccionBoton?transaccion=<?php echo $transaccion->referencia;?>&estado=<?php echo ESTADO_TRANSACCION_CANCELADO; ?>&pesos=<?php echo $transaccion->pesos;?>" class="btn btn-danger btn-outline col-lg-offset-1 pull-right">Eliminar</a><?php }?>
        </div>
    <?php }elseif($botones && $transaccion->estado != ESTADO_TRANSACCION_CANCELADO && $transaccion->estado != ESTADO_TRANSACCION_PROCESADO){?>
        <a href="Transacciones/estadoTransaccionBoton?transaccion=<?php echo $transaccion->referencia;?>&estado=<?php echo ESTADO_TRANSACCION_VERIFICACION; ?>" class="btn btn-warning btn-outline">Espera de Correccion</a>
    <?php }
        if(isset($botonPendiente) && $botonPendiente){ ?>
            <a href="transacciones/pendientes" class="btn btn-primary pull-right btn-outline">Volver</i></a>
    <?php
        }else if(isset($tienda) && $tienda && isset($desde) && $desde && isset($hasta) && $hasta){?>
            <a href="transacciones/procesadas?tienda=<?php echo $tienda;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>" class="btn btn-primary pull-right btn-outline">Volver</i></a>
    <?php
        }
    ?>
</div>