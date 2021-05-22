<!-- <div class="container-fluid" id="divCliente"> -->
<div class="container-fluid">
    <div class="container" >
        <div class="row">
            
            <div class="col-lg-6">
                
                <div class="col-md-8">
                    <!-- <div class="row">
                        <h3>Ingrese el número de su transaccion</h3>
                    </div> -->
                    <div class="login-panel panel panel-yellow">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ingrese el Número de su Transacción</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="POST" action="">
                                <div class="input-group">
                                    <input class="form-control" name="referencia" id="transaccionClientes" type="number" placeholder="Numero de Referencia">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" name="buscarTransaccion" id="buscarTransaccion">
                                            <i class="fa fa-search fafw"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="col-md-10 ">
                    <!-- <div class="row">
                        <h3>Ingrese el número de su transaccion</h3>
                    </div> -->
                    <div class="login-panel panel panel-green">
                        <div class="panel-heading">
                            <h3 class="panel-title">Calcule Aquí su Envio</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="POST" action="">
                                <div class="form-group input-group">
                                    <span class="input-group-addon">$</span>
                                    <input name="pesosHome" id="pesosHome" type="text" class="form-control" placeholder="Monto en Pesos">
                                </div>
                                    <p name="tasaActual" id="tasaActual" class="text-success">Tasa de Cambio Actual: <?php echo $tasa->valor;?></p>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Bs.S</span>
                                    <input name="bsHome" id="bsHome" type="text" class="form-control" value="0">
                                </div>
                                <input name="tasa" id="tasa" type="text" class="form-control" value="<?php echo $tasa->valor;?>" style="display: none;">
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div id="transaccion" class="col-lg-10 col-lg-offset-1"></div>
        </div>
    </div>
</div>
<!-- </div> -->
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