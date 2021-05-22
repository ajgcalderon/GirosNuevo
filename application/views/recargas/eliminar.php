<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Recarga</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
                Eliminar Recarga
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- <h2 class="text-dark">Transaccion</h2> -->
                    <div class="alert alert-danger col-lg-4 col-lg-offset-3"><center>Está <strong>eliminando</strong> una recargar, por favor confirmar</center></div>
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-offset-3 col-lg-6 row">
                            <div class="col-lg-6">
                                <strong>Fecha</strong><br>
                                <strong>N° Referencia</strong><br>
                                <strong>Monto</strong><br>
                                <strong>Tienda</strong><br>
                                
                                <!-- <strong>Tienda</strong><br>
                                <strong>Girador</strong><br> -->
                            </div>
                            <div class="col-lg-6">
                                <?php echo $recarga->fecha;?><br>
                                <?php echo $recarga->referencia;?><br>
                                <?php echo '$ ' . number_format($recarga->monto,2,',','.');?><br>
                                <?php echo $recarga->nombre . ' ' . $recarga->apellido;?><br>
                                
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
<div class="col-lg-offset-4 col-lg-3">
    <p>
        <a href="Recargas/delete?id=<?php echo $recarga->id;?>&referencia=<?php echo $recarga->referencia;?>&monto=<?php echo $recarga->monto;?>&tiendaRecarga=<?php echo $recarga->tienda;?>&tienda=<?php echo $tienda;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>" class="btn btn-danger btn-outline">Si, Eliminar</a>
        <a href="Recargas/detalles?referencia=<?php echo $recarga->referencia;?>&tienda=<?php echo $tienda;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>" class="btn btn-primary pull-right btn-outline">Volver</i></a>
    </p>
</div>