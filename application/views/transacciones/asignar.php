<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Transacciones</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                Asignar Transacciones
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" action="Transacciones/asignacion" id="asignarTransacciones">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Fecha</th>
                                <th>Referencia</th>
                                <th>Cedula</th>
                                <th>Beneficiario</th>
                                <th>Banco</th>
                                <th>Nº Cuenta</th>
                                <th>Monto</th>
                                <th>Tasa</th>
                                <th>$Col</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                foreach($transacciones as $transaccion){
                                    if ($i%2!=0){
                            ?>
                                        <tr class="odd">
                            <?php   }else{?>
                                        <tr class="even">
                            <?php   }?>
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="transacciones[]" value="<?php echo $transaccion->referencia;?>">
                                                    </label>
                                                </div>
                                            </td>
                                            <td><?php echo $transaccion->fecha;?></td>
                                            <td><?php echo $transaccion->referencia;?></td>
                                            <td><?php echo $transaccion->cedula;?></td>
                                            <td><?php echo $transaccion->beneficiario;?></td>
                                            <td><?php echo $transaccion->banco;?></td>
                                            <td><?php echo $transaccion->cuenta;?></td>
                                            <td><?php echo 'Bs.S ' . $transaccion->monto;?></td>
                                            <td><?php echo $transaccion->tasa;?></td>
                                            <td><?php echo $transaccion->pesos;?></td>
                                        </tr>
                            <?php
                            $i =+1;    
                                }
                            ?>
                            
                        </tbody>
                        
                    </table>
                    <select class="form-control" name="girador" id="selectGirador">
                            <option value="">Seleccione un Girador</option>
                        <?php 
                            foreach ($giradores as $girador) {
                        ?>
                            <option value="<?php echo $girador->id;?>"><?php echo $girador->nombre . ' ' . $girador->apellido . ' ' . $girador->cedula;?></option>
                        <?php    
                            }    
                        ?>
                    </select>
                    <div class="divider">&nbsp;</div>
                    <center><button type="submit" class="btn btn-primary btn-lg" id="botonAsignar" disabled>Asignar</button></center>
                </form>
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