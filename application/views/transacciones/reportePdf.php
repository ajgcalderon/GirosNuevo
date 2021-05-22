<head>
    <style>
        body{
            background-image: url("recursos/img/logo_giralo.png");
            background-position: center top; 
            background-size: 5px;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #contenido{
            background-color: #FFFFFFDD;
            padding-bottom: 150px;
        }
    </style>
</head>

<body>
    <div id="contenido">
        <div class="row">
            <center><h1 class="page-header">Transaccion Nº <?php echo $transaccion->referencia;?></center>
        </div>
        <div class="row">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <center>
                            <table border="0" style="margin: auto; padding: 5px">
                                <tr>
                                    <td><strong>Fecha</strong></td>
                                    <td><?php echo $transaccion->fecha;?></td>
                                </tr>
                                <tr>
                                    <td><strong>N° Referencia</strong></td>
                                    <td><?php echo $transaccion->referencia;?></td>
                                </tr>
                                <tr>
                                    <td><strong>Monto</strong></td>
                                    <td><?php echo 'Bs.S ' . number_format($transaccion->monto,2,',','.');?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tasa</strong></td>
                                    <td><?php echo $transaccion->tasa;?></td>
                                </tr>
                                <tr>
                                    <td><strong>Pesos</strong></td>
                                    <td><?php echo '$COP ' . number_format($transaccion->pesos,2,',','.');?></td>
                                </tr>
                                <tr>
                                    <td><strong>Estado</strong></td>
                                    <td><?php echo $transaccion->estado;?></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><strong>Beneficiario</strong></td>
                                    <td><?php echo $transaccion->beneficiario;?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cedula</strong></td>
                                    <td><?php echo $transaccion->cedula;?></td>
                                </tr>
                                <tr>
                                    <td><strong>Banco</strong></td>
                                    <td><?php echo $transaccion->banco;?></td>
                                </tr>
                                <tr>
                                    <td><strong>Numero de Cuenta</strong></td>
                                    <td><?php echo substr($transaccion->cuenta,0,4) . '-' . substr($transaccion->cuenta,4,4) . '-' . substr($transaccion->cuenta,8,4) . '-' . substr($transaccion->cuenta,12,4) . '-' . substr($transaccion->cuenta,16,4);?></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><strong>Cliente</strong></td>
                                    <td><?php echo $transaccion->nombreCliente;?></td>
                                </tr>
                                <tr>
                                    <td><strong>ID Cliente</strong></td>
                                    <td><?php echo $transaccion->cedulaCliente;?></td>
                                </tr>
                                <tr>
                                    <td><strong>Telefono</strong></td>
                                    <td><?php echo $transaccion->telefono;?></td>
                                </tr>
                            </table>
                            <br><br>
                            <p>Podrá hacer seguimiento al estado de su Giro mediante nuestra pataforma http://giralovzla.net las 24 horas del día, con solo ingresar el número de referencia de su giro. O contáctanos al número +57 304 5890705.</p>
                        </center>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    
</body>