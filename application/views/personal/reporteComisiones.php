<div class="row">
    <div class="col-lg-12">
<h1 class="page-header"><?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>Comisiones<?php }else{ ?>Reporte de Compras<?php }?></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Resumen
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form action="usuarios/reporteDeComisiones" method="get" class="form-inline row">
                <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                    <div class="form-group col-lg-offset-1 col-lg-3 ">
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
                    <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                        <div class="form-group col-lg-3 ">
                    <?php }else{?>
                        <div class="form-group col-lg-offset-3 col-lg-3 ">
                    <?php } ?>
                            <div class="input-group">
                            <label for="desde">Desde</label>
                                <input type="date" name="desde" class="form-control">
                                <!-- <span class="input-group-btn">
                                    <button type="submit" id="busqueda" class="btn btn-default">
                                        <i class="fa fa-search fafw"></i>
                                    </button>
                                    <input type="button" value="<i class='fa fa-search fafw'></i>" class="btn btn-default" id="busqueda">
                                </span> -->
                            </div>
                        </div>
                    <div class="form-group  col-lg-3">
                        <div class="input-group">
                            <label for="hasta">Hasta</label>
                            <input type="date" name="hasta" class="form-control">
                            <!-- <span class="input-group-btn">
                                <button type="submit" id="busqueda" class="btn btn-default">
                                    <i class="fa fa-search fafw"></i>
                                </button>
                                 <input type="button" value="<i class='fa fa-search fafw'></i>" class="btn btn-default" id="busqueda"> 
                            </span> -->
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><span class="fa fafw fa-search"></span></button>
                </form>
                <br><br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Total Recargas</th>
                            <th>Comisiones</th>
                            <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?><th>Grupo</th><?php }?>
                            <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR){?><th>Subgrupo</th><?php }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($cuentas as $cuenta){
                           
                        ?>
                            <tr>
                                <td><?php if($desde==$hasta){echo $desde;}else{echo $desde . ' - ' . $hasta;}?></td>
                                <td><a href="Recargas?tienda=<?php echo $cuenta['id'];?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>&tiendaSeleccionada=<?php echo $tiendaSeleccionada;?>"><?php echo $cuenta['nombre'] ?></a></td>
                                <td><?php echo '$ ' . number_format($cuenta['pesos'],2,',','.');?></td>
                                <td><?php echo '$ ' . number_format($cuenta['comision'],2,',','.');?></td>
                                <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){?><td><a href="Usuarios/reporteDeComisiones?grupo=<?php echo $cuenta['grupo'];?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>"><?php echo $cuenta['grupo'];?></a></td><?php }?>
                        <?php if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR){?><td><?php if($cuenta['subgrupo']){?><a href="Usuarios/reporteDeComisiones?subgrupo=<?php echo $cuenta['subgrupo'];?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>"><?php echo $cuenta['subgrupo'];?></a><?php }?></td><?php }?>
                            </tr>
                            
                        <?php }
                        ?>
                            
                    </tbody>
                </table>
                <!-- /.table-responsive -->
                <?php if($this->session->userdata('tipo') != USUARIO_TIENDA){?>
                    <a href="Usuarios/export?tienda=<?php echo $tiendaSeleccionada;?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>" target="_blank" class="btn btn-success"><i class="fa fafw fa-file-excel-o"></i> Exportar a Excel</a>
                <?php }else{?>
                    <a href="Usuarios/export?tienda=<?php echo $this->session->userdata('id');?>&desde=<?php echo $desde;?>&hasta=<?php echo $hasta;?>" target="_blank" class="btn btn-success"><i class="fa fafw fa-file-excel-o"></i> Exportar a Excel</a>
                <?php }?>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
                <?php if(isset($boton) && $boton){?>
                    <a href="usuarios/comisionesTiendas" class="btn btn-default pull-right"><span class="fa fa-fw fa-reply"></span> Volver</a>
                <?php }?>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div>
    
</div>