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
                Modificar
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" action="Recargas/update?id=<?php echo $recarga->id;?>&montoAnterior=<?php echo $recarga->monto;?>" id="recargarCuentas">
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label for="tienda">Tienda</label>
                        <select name="tienda" id="tienda" class="form-control">
                            <option value="">Seleccione una tienda</option>
                            <?php foreach($listaTiendas as $tienda){?>
                            <option value="<?php echo $tienda->id;?>" <?php if($tienda->id == $recarga->tienda){?>selected<?php }?>><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></option>
                            <?php }?>
                        </select>      
                    </div>
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label for="fecha">Fecha</label>
                        <input class="form-control" name="fecha" id="fecha" type="date" value="<?php echo $recarga->fecha; ?>" required>
                    </div>
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label for="comprobante">Comprobante</label>
                        <input class="form-control" name="comprobante" id="comprobante" value="<?php echo $recarga->referencia; ?>" type="text" placeholder="Numero de referencia" required>
                    </div>
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label>Monto</label>
                        <input class="form-control " name="monto" id="monto" type="text" placeholder="Monto" value="<?php echo $recarga->monto; ?>" required>
                    </div>
                    <div class="divider">&nbsp;</div>
                    <button type="submit" class="btn btn-outline btn-primary btn-lg col-lg-offset-5 col-lg-2">Actualizar</button>
                </form>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        <a href="Recargas/detalles?referencia=<?php echo $recarga->referencia;?>&tienda=<?php echo $tiendaSelect;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>" class="btn btn-primary btn-outline col-lg-1 pull-right">Volver</a>
    </div>
    <!-- /.col-lg-12 -->
</div>