<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Registro de Reclamos</h1>
    </div>
    <!-- /.col-lg-12 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-red">
                <div class="panel-heading"> 
                    Reclamo
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div id="transaccion" class="col-lg-12">

                        </div>
                        <div class="col-lg-3"></div>                        
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                            
                            <form role="form" method="post" action="Reclamos/registrar" id="registrarReclamos">
                                <div class="form-group">
                                    <label>Numero de Referencia</label>
                                    <div class="input-group">
                                        <input class="form-control" name="referencia" id="referenciaTransaccion" type="number" placeholder="Numero de Referencia">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default" name="buscarTransaccion" id="buscarTransaccion">
                                                <i class="fa fa-search fafw"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="observacion">Observación</label>
                                    <textarea name="observacion" id="observacion" class="form-control" rows="3" maxlength="255"></textarea>
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