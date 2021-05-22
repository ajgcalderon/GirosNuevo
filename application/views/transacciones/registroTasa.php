<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Tasa</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Actualización de Tasa
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="alert alert-info">
                <?php echo 'La tasa de cambio actual es: ' . $tasa->valor;?>
            </div>
                <?php 
                    echo form_open('Transacciones/registrarTasa',array('method' => 'post','role' => 'form'));
                ?>
                    <div class="form-group">
                        <?php
                        echo form_label('Tasa','tasa',array('for'=>'tasa'));
                        echo form_input('tasa','','class="form-control" placeholder="Tarifa Nueva"');
                        ?>
                    </div>
                    <center><input type="submit" value="Actualizar" class="btn btn-primary btn-outline"></center>
                <?php 
                    echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>