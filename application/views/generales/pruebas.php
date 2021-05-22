<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <h1>Pruebas</h1>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Subir Archivo
            </div>
            <div class="panel-body">
                <div class="col-lg-3">&nbsp;</div>
                <div class="col-lg-6">
                    <?php 
                        echo $error;
                        echo form_open('Generales/subir', 'role="form" method="post" type="submit" enctype="multipart/form-data"'); 
                    ?>
                        <div class="form-group">
                            <label for="capture">Capture</label>
                            <input type="file" name="userfile" size="20" />
                        </div>
                        <input type="submit" value="upload" class="btn btn-primary"/>
                    </form>
                </div>    
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        Panel de Prueba
    </div>
    <div class="panel-body">
    <div id="textoPrueba">
    
    </div>
        <center><button type="button" id="prueba" class="btn btn-primary">Prueba</button></center>
        <input type="submit" value="Enviar" class="btn btn-primary" id="botonEnviar" disabled>
    </div>
</div>