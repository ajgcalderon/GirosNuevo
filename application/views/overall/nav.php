<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo base_url(); ?>">GIRALO EXPRESS VENEZUELA</a>
    </div>
    <!-- /.navbar-header -->
    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <li class="dropdown pull-right">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                <!-- <h3>Iniciar Sesión</h3> -->
            </a>
            <ul class="dropdown-menu dropdown-user">
                <?php if ($this->session->userdata('login')) {?>
                    <li><center><i class="fa fa-user fa-fw"></i><?php echo '  ' . $this->session->userdata('nombreUsuario'); ?></center></li>
                    <li class="divider"></li>
                    <li><a href="usuarios/changePass"><center><i class="fa fa-edit fa-fw"></i> Change Password</center></a>
                    <li><a href="login/logout"><center><i class="fa fa-sign-out fa-fw"></i> Logout</center></a>
                    </li>
                <?php } else {?>
                    <li><a href="login/inicioSesion"><center><i class="fa fa-sign-in fa-fw"></i> Login</center></a>
                    </li>
                <?php }?>
                
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    

<?php 
if ($this->session->userdata('login')) {
?>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li >
                    <a href="<?php echo base_url(); ?>" class="text-primary"><i class="fa fa-dashboard fa-fw"></i> Generales</a>
                </li>

<?php
    switch ($this->session->userdata('tipo')) {
        case USUARIO_ADMINISTRADOR:
?>
        
                   
                    <li>
                        <a href="#" class="text-success"><i class="fa fa-share-square-o fa-fw"></i> Transferencias<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="Transferencias" class="text-success">Reporte General</a>
                            </li>
                            <li>
                                <a href="Transferencias/devoluciones" class="text-success">Devueltas</a>
                            </li>
                            <li>
                                <a href="Transferencias/RegistroDevoluciones" class="text-success">Registrar Devolución</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li class="">
                        <a href="#" class="text-warning"><i class="fa fa-list-alt fa-fw "></i> Transacciones<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="transacciones/pendientes" class="text-warning">Listado</a>
                            </li>
                            <li>
                                <a href="transacciones/registro" class="text-warning">Hacer Giro</a>
                            </li>
                            <li>
                                <a href="transacciones/asignarTransaccion" class="text-warning">Asignar</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#" class="text-danger"><i class="fa fa-support fa-fw"></i> Reclamos<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="Reclamos" class="text-danger">Listado</a>
                            </li>
                            <li>
                                <a href="Reclamos/registro" class="text-danger">Registrar</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="text-secondary"><i class="fa fa-group fa-fw"></i> Personal<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="Usuarios/comisiones" class="text-secondary">Comisiones Giradores</a>
                            </li>
                            <li>
                                <a href="Usuarios/reporteDeComisiones" class="text-secondary">Comisiones Tienda (Consignacion)</a>
                            </li>
                            <li>
                                <a href="Usuarios/comisionesTiendas" class="text-secondary">Comisiones Tiendas (Recaudos)</a>
                            </li>
                            <li>
                                <a href="Usuarios/listadoDeCuentasTiendas" class="text-secondary">Cuentas</a>
                            </li>
                            <li>
                                <a href="Recargas/diferirSaldo" class="text-secondary">Diferir Saldo</a>
                            </li>
                            <li>
                                <a href="Clientes" class="text-secondary">Listado de Clientes</a>
                            </li>
                            <li>
                                <a href="Recargas" class="text-secondary">Listado de Recargas Tiendas</a>
                            </li>
                            <li>
                                <a href="Usuarios/listado" class="text-secondary">Listado de Usuarios</a>
                            </li>
                            <li>
                                <a href="Usuarios/recargarTienda" class="text-secondary">Recarga de Tienda</a>
                            </li>
                            <li>
                                <a href="Usuarios/registroPersonal" class="text-secondary">Registrar Personal</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="Transacciones/tasa" class="text-primary"><i class="fa fa-bar-chart-o fa-fw"></i> Tasa</a>
                    </li>
                    <li>
                        <a href="#" class="text-secondary"><i class="fa fa-envelope fa-fw"></i> Avisos<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="Mensajes" class="text-secondary">Listado</a>
                            </li>
                            <li>
                                <a href="Mensajes/formulario" class="text-secondary">Registrar</a>
                            </li>
                        </ul>
                    </li>
<?php
            break;
        case USUARIO_SUPERVISOR:
?>
                    <li>
                        <a href="#" class="text-success"><i class="fa fa-share-square-o fa-fw"></i> Transferencias<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="Transferencias" class="text-success">Reporte General</a>
                            </li>
                            <li>
                                <a href="Transferencias/Registro" class="text-success">Registro</a>
                            </li>
                            <li>
                                <a href="Transferencias/devoluciones" class="text-success">Devueltas</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#" class="text-warning"><i class="fa fa-list-alt fa-fw"></i> Transacciones<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="transacciones/pendientes" class="text-warning">Listado</a>
                            </li>
                            <li>
                                <a href="transacciones/asignarTransaccion" class="text-warning">Asignar</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="Reclamos" class="text-danger"><i class="fa fa-support fa-fw "></i> Reclamos</a>
                    </li>
                    <li>
                        <a href="Usuarios/comisiones" class="text-secondary"><i class="fa fa-bank fa-fw"></i> Comisiones</a>
                    </li>
                    
<?php           
            break;
        case USUARIO_GIRADOR:
?>
                    <li>
                        <a href="#" class="text-success"><i class="fa fa-share-square-o fa-fw"></i>Transferencias<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="Transferencias" class="text-success">Reporte General</a>
                            </li>
                            <li>
                                <a href="Transferencias/Registro" class="text-success">Registro</a>
                            </li>
                            <li>
                                <a href="Transferencias/Devoluciones" class="text-success">Devoluciones</a>
                            </li>
                            <li>
                                <a href="Transferencias/RegistroDevoluciones" class="text-success">Registrar Devolución</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li><li>
                        <a href="transacciones/pendientes" class="text-warning"><i class="fa fa-list-alt fa-fw"></i>Transacciones</a>
                    </li>
                    <li>
                        <a href="Reclamos" class="text-danger"><i class="fa fa-support fa-fw"></i> Reclamos</a>
                    </li>
                    <li>
                        <a href="Usuarios/listadoDeCuentas" class="text-secondary"><i class="fa fa-bank fa-fw"></i> Cuentas</a>
                    </li>
                    <li>
                        <a href="Usuarios/comisiones" class="text-secondary"><i class="fa fa-bank fa-fw"></i> Mis Comisiones</a>
                    </li>
<?php
            break;
        case USUARIO_TIENDA:
?>
                    <li>
                        <a href="Transferencias" class="text-success"><i class="fa fa-share-square-o fa-fw"></i> Transferencias</a>
                    </li>
                    <li class="">
                        <a href="#" class="text-warning"><i class="fa fa-list-alt fa-fw "></i> Transacciones<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="transacciones/pendientes" class="text-warning">Listado</a>
                            </li>
                            <li>
                                <a href="transacciones/registro" class="text-warning">Hacer Giro</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#" class="text-danger"><i class="fa fa-support fa-fw"></i> Reclamos<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="Reclamos" class="text-danger">Listado</a>
                            </li>
                            <li>
                                <a href="Reclamos/registro" class="text-danger">Registrar</a>
                            </li>
                        </ul>
                    </li>
                    <?php if ($this->session->userdata('grupo') != GRUPO_EMPRESA) { ?>
                        <li>
                            <a href="#" class="text-secondary"><i class="fa fa-bank fa-fw"></i> Cuenta<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="Usuarios/listadoDeCuentasTiendas" class="text-secondary"> Saldo</a>
                                </li>
                                <li>
                                    <a href="Recargas/registroConsignacion" class="text-secondary"> Registrar Consignacion Propia</a>
                                </li>
                                <li>
                                    <a href="Recargas" class="text-secondary"> Reporte de Compras</a>
                                </li>
                            </ul>
                        </li>
                    <?php }?>
                    

<?php
            break;
            case USUARIO_DISTRIBUIDOR:
?>
                <li>
                    <a href="#" class="text-success"><i class="fa fa-share-square-o fa-fw"></i> Transferencias<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="Transferencias" class="text-success">Reporte General</a>
                        </li>
                        <li>
                            <a href="Transferencias/devoluciones" class="text-success">Devueltas</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li class="">
                    <a href="#" class="text-warning"><i class="fa fa-list-alt fa-fw "></i> Transacciones<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="transacciones/pendientes" class="text-warning">Listado</a>
                        </li>
                        <li>
                            <a href="transacciones/registro" class="text-warning">Hacer Giro</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#" class="text-danger"><i class="fa fa-support fa-fw"></i> Reclamos<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="Reclamos" class="text-danger">Listado</a>
                        </li>
                        <li>
                            <a href="Reclamos/registro" class="text-danger">Registrar</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="text-secondary"><i class="fa fa-group fa-fw"></i> Personal<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="Usuarios/listadoDeCuentasTiendas" class="text-secondary">Cuentas</a>
                        </li>
                        <li>
                            <a href="Clientes" class="text-secondary">Listado de Clientes</a>
                        </li>
                        <li>
                            <a href="Recargas" class="text-secondary">Listado de Recargas Tiendas</a>
                        </li>
                        <li>
                            <a href="Usuarios/listado" class="text-secondary">Listado de Usuarios</a>
                        </li>
                        <li>
                            <a href="Recargas/registroConsignacion" class="text-secondary">Registrar Consignacion Propia</a>
                        </li>
                        <li>
                            <a href="Usuarios/recargarTienda" class="text-secondary">Recarga de Tienda</a>
                        </li>
                        <li>
                            <a href="Usuarios/registroPersonal" class="text-secondary">Registrar Personal</a>
                        </li>
                        <li>
                            <a href="Usuarios/reporteDeComisiones" class="text-secondary">Reporte de Comisiones</a>
                        </li>
                    </ul>
                </li>
<?php
            break;
            case USUARIO_SUBDISTRIBUIDOR:
?>
                <li>
                    <a href="#" class="text-success"><i class="fa fa-share-square-o fa-fw"></i> Transferencias<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="Transferencias" class="text-success">Reporte General</a>
                        </li>
                        <li>
                            <a href="Transferencias/devoluciones" class="text-success">Devueltas</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li class="">
                    <a href="#" class="text-warning"><i class="fa fa-list-alt fa-fw "></i> Transacciones<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="transacciones/pendientes" class="text-warning">Listado</a>
                        </li>
                        <li>
                            <a href="transacciones/registro" class="text-warning">Hacer Giro</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#" class="text-danger"><i class="fa fa-support fa-fw"></i> Reclamos<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="Reclamos" class="text-danger">Listado</a>
                        </li>
                        <li>
                            <a href="Reclamos/registro" class="text-danger">Registrar</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="text-secondary"><i class="fa fa-group fa-fw"></i> Personal<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="Usuarios/listadoDeCuentasTiendas" class="text-secondary">Cuentas</a>
                        </li>
                        <li>
                            <a href="Clientes" class="text-secondary">Listado de Clientes</a>
                        </li>
                        <li>
                            <a href="Recargas" class="text-secondary">Listado de Recargas Tiendas</a>
                        </li>
                        <li>
                            <a href="Usuarios/listado" class="text-secondary">Listado de Usuarios</a>
                        </li>
                        <li>
                            <a href="Recargas/registroConsignacion" class="text-secondary">Registrar Consignacion Propia</a>
                        </li>
                        <li>
                            <a href="Usuarios/recargarTienda" class="text-secondary">Recarga de Tienda</a>
                        </li>
                        <li>
                            <a href="Usuarios/registroPersonal" class="text-secondary">Registrar Personal</a>
                        </li>
                        <li>
                            <a href="Usuarios/reporteDeComisiones" class="text-secondary">Reporte de Comisiones</a>
                        </li>
                    </ul>
                </li>
<?php
            break;
    }
?>
            </ul>
        </div>
            <!-- /.sidebar-collapse -->
    </div>
    </nav>
    <div id="page-wrapper">
<?php
}else{
?>
</nav>
<div class="container">
<?php
}
?>
                
