    <div class="container">
        <div class="row">
                
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-primary">
                    <?php if(isset($error)){ ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <center><?php echo $error;?></center>
                        </div>
                    <?php }?>
                    <div class="panel-heading">
                        <h3 class="panel-title">Por Favor Ingresa los Datos</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="POST" action="login/index">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!-- <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Recuerdame
                                    </label>
                                </div> -->
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" value="Ingresar" class="btn btn-lg btn-success btn-block">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
