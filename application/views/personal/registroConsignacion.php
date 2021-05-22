<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registrar Consignación</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cuentas
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            
            <?php 
                        
                           if(isset($error)){
                               echo $error;
                           }
                           echo form_open_multipart(base_url()."Recargas/registrarConsignacion",'role="form" method="post" id="registroConsignacion"');
                       ?>
                    <?php if(isset($tiendaRecargada)){?>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            La recarga fue registrada Exitosamente.
                        </div>
                    <?php }?>
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label for="fecha">Fecha</label>
                        <input class="form-control" name="fecha" id="fecha" type="date" required>
                    </div>
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label for="referencia">Referencia</label>
                        <input class="form-control" class="form-control" name="referencia" id="referencia" type="text" required>
                    </div>
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label for="comprobante">Comprobante</label>
                        <input type="file" name="comprobante" id="comprobante" required>
                    </div>
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label>Monto</label>
                        <input class="form-control " name="pesos" id="pesos" type="text" placeholder="Monto" required>
                    </div>
                    <div class="divider">&nbsp;</div>
                    <button type="submit" class="btn btn-outline btn-primary btn-lg col-lg-offset-5 col-lg-2" id="btn-submit-consig">Registrar</button>
                </form>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        <!-- <a href="<?php // base_url()?>/recursos/recaudo_callvoipgirosvenezuela.pdf" download="recaudo callvoipgirosvenezuela.pdf" class="btn btn-primary btn-small">Descargar Tarjeta de Recaudo</a> -->
    </div>
    <!-- /.col-lg-12 -->
</div>