<?php if($this->session->userdata('login')){?>
    </div>
<?php }else{?>
            <footer class="navbar navbar-default navbar-fixed-bottom">
                <div class="navbar-footer">
                    <center><a href="#" class="navbar-brand text-center">Contactanos al <i class="fa fa-whatsapp fa-fw text-green"></i> +57 304 5890705</a></center>
                </div>
            </footer>
        </div>
    </div>
<?php }?>  


<script src="<?php echo base_url();?>recursos/vendor/jquery/jquery.min.js"></script>
<!-- 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url();?>recursos/vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="<?php echo base_url();?>recursos/vendor/metisMenu/metisMenu.min.js"></script>

<!-- Morris Charts JavaScript -->
<script src="<?php echo base_url();?>recursos/vendor/raphael/raphael.min.js"></script>
<script src="<?php echo base_url();?>recursos/vendor/morrisjs/morris.min.js"></script>
<script src="<?php echo base_url();?>recursos/data/morris-data.js"></script>

<!-- Custom Theme JavaScript -->
<script src="<?php echo base_url();?>recursos/dist/js/sb-admin-2.js"></script>

<!-- Validador de Formularios -->

<script src="<?php echo base_url();?>recursos/js/Comprobacion.js"></script>

<!-- DataTables JavaScript -->
<script src="<?php echo base_url();?>recursos/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>recursos/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>recursos/vendor/datatables-responsive/dataTables.responsive.js"></script>
<?php if($this->session->userdata('login')){?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
<script src="<?php echo base_url();?>recursos/vendor/appScript/session_logout.js"></script>
<?php }?>
<script src="<?php echo base_url();?>recursos/vendor/appScript/constants.js"></script>
<script src="<?php echo base_url();?>recursos/vendor/appScript/ajax3.js"></script>
<script src="<?php echo base_url();?>recursos/vendor/appScript/validacion.js"></script>

<script>
    $(function() {
        $('#dataTables').DataTable({
            responsive: true
        });
        $('.dataTables').DataTable({
            responsive: true
        });
    });
</script>
</body>

</html>