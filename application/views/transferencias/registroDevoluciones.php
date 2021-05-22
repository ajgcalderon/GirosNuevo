<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Devoluciones</h1>
    </div>
    <!-- /.col-lg-12 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-green">
                <div class="panel-heading"> 
                    Devolucion
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div id="devolucion" class="col-lg-12">
                        
                        </div>
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            <?php echo form_open_multipart('Transferencias/registrarDevolucion','role="form" method="post" id="registrarDevolucion"');?>
                                <div class="form-group">
                                    <label for="referencia">Numero de Referencia (Giro)</label>
                                    <input class="form-control" name="referencia" id="referenciaDevolucion" type="number" placeholder="Numero de Referencia">
                                </div>
                                <div class="form-group">
                                    <label>Capture</label>
                                    <input type="file" name="capture" id="capture">
                                    <img id="vistaPrevia" src="https://via.placeholder.com/468x60?text=Capture+de+la+Transferencia" alt="Capture de la Transferencia" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"/>
                                </div>
                                <div class="form-group">
                                    <label for="motivo" class="control-label">Motivo</label>
                                    <Textarea name="motivo" id ="motivo" class="form-control" required></Textarea>
                                </div>
                                <button type="submit" class="btn btn-default">Registrar</button>
                                <button type="reset" class="btn btn-danger">Borrar Todo</button>
                            </form>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>  
</div>