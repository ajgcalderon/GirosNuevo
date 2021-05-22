<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Clientes</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Listado
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <form action="Clientes" method="get" class="form-inline row">
                <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                    <div class="form-group col-lg-offset-4 col-lg-3 ">
                        <div class="input-group">
                            <label for="tienda">Tienda</label>
                            <select name="tienda" id="tienda" class="form-control">
                                <option value="">Seleccione una Tienda</option>
                                <?php foreach ($listaTiendas as $tienda) { ?>
                                    <option value="<?php echo $tienda->id;?>" <?php if($tiendaSeleccionada == $tienda->id){echo "Selected";}?>><?php echo $tienda->nombre . ' ' . $tienda->apellido;?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                                <?php } ?>
                    <button type="submit" class="btn btn-primary"><span class="fa fafw fa-search"></span></button>
                </form>
                <br><br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Numero Telefonico</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                

                                    foreach($clientes as $cliente){
                                        if ($i%2!=0){
                                            ?>
                                            <tr class="odd">
                            <?php       }else{?>            
                                            <tr class="even">
                            <?php       }?>
                                                <td><?php echo $cliente->nombre;?></td>
                                                <td><?php echo $cliente->telefono;?></td>
                                            </tr>
                            <?php   
                                        $i =+1;
                                    }?>
                                
                        </tbody>
                </table>
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