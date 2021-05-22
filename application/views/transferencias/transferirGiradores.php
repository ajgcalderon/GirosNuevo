<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-green">
                <div class="panel-heading">
                    A Otro Girador
                </div>
                <div class="panel-body">
                <div class="col-lg-3">&nbsp;</div>
                <div class="col-lg-6">
                <?php 
                                if(isset($error)){
                                    echo $error;
                                }
                                echo form_open_multipart('Transferencias/transferirGirador','role="form" method="post" id="TransferirGirador"');
                            ?>
                        <div class="form-group">
                            <label>Numero de Referencia</label>
                            <input class="form-control" name="referencia" type="text" placeholder="Numero de Referencia" required>
                        </div>
                        <div class="form-group ">
                            <label for="monto">Monto </label>
                            <input type="text" name="monto" id="monto" class="form-control" placeholder="Bs.S" required>
                        </div>
                        <div class="form-group">
                            <label>Capture</label>
                            <input type="file" name="capture" id="capture">
                        </div>
                        <div class="form-group">
                            <label>Fecha</label>
                            <input class="form-control" name="fecha" type="date" placeholder="AAAA/MM/DD" required>
                        </div>
                        <div class="form-group form-inline">
                            <label>Cuenta Debitada</label>
                            <select class="form-control" name="cuentaDebitada">
                                <option value="">Seleccione una cuenta</option>
                                <?php 
                                    foreach ($cuentas as $cuenta) {
                                ?>
                                    <option value="<?php echo $cuenta->numero;?>"><?php echo $cuenta->banco . ' - ' . $cuenta->tipo . ' - **' . substr($cuenta->numero,16,4);?></option>
                                <?php    
                                    }    
                                ?>
                            </select>
                        </div>
                        <div class="form-group form-inline">
                            <label for="girador">Girador</label>
                            <div class="input-group">
                                <select name="girador" class="form-control" id="girador">
                                    <option value="">Seleccione un Girador</option>
                                    <?php foreach ($giradores as $girador) {?>
                                        <option value="<?php echo $girador->id;?>" id="opciones[]"><?php echo $girador->nombre . ' ' . $girador->apellido;?></option>
                                    <?php }?>
                                </select>
                                <!-- <button type="button"  class="btn btn-default btn-small" id="busqueda" onclick="mostrarCuentas()"><i class="fa fa-search fa-fw"></i></button> -->
                                <span class="input-group-btn">
                                    <button type="button" id="busqueda" class="btn btn-default">
                                        <i class="fa fa-search fafw"></i>
                                    </button>
                                    <!-- <input type="button" value="<i class='fa fa-search fafw'></i>" class="btn btn-default" id="busqueda"> -->
                                </span>
                            </div>
                        </div>
                        <div class="form-group" id="cuentas">
    
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
    
                </div>    
                </div>
            </div>
        </div>
    </div>
</div>