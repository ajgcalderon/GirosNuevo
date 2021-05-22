<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Diferir Saldo</h1>
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
            <?php echo form_open_multipart('Recargas/diferir','role="form" method="post" id="registroConsignacion"');?>
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                        <thead>
                            <tr>
                                <th>Tienda</th>
                                <th>Saldo Disponible</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                foreach($tiendas as $tienda){
                                    if ($i%2!=0){
                                        ?>
                                        <tr class="odd">
                                        
                                            <td><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></td>
                                            <td><?php echo '$COP ' . number_format($tienda->pesos,2,',','.');?></td>
                                        </tr>
                            <?php   }else{?>
                                        <tr class="even">
                                            
                                            <td><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></td>
                                            <td><?php echo '$COP ' . number_format($tienda->pesos,2,',','.');?></td>
                                        </tr>
                            <?php   
                                    }
                                    $i =+1;
                                }
                            ?>
                            
                        </tbody>
                    </table>
                    <?php if(isset($tiendaRecargada)){?>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            La recarga fue registrada Exitosamente.
                        </div>
                    <?php }?>
                    <div class="form-group col-lg-offset-4 col-lg-4">
                        <label for="tienda">Tienda</label>
                        <select name="tienda" id="tiendaDiferir" class="form-control" required>
                            <option value="">Seleccione una tienda</option>
                            <?php foreach($tiendas as $tienda){?>
                                <option value="<?php echo $tienda->id;?>"><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></option>
                            <?php }?>
                        </select>      
                    </div>
                    <div id="saldoDisponible">
                    
                    </div>
                    <div class="divider">&nbsp;</div>
                    <button type="submit" class="btn btn-outline btn-primary btn-lg col-lg-offset-5 col-lg-2">Diferir</button>
                </form>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>