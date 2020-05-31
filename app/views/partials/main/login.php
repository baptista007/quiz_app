
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-4">
            <div class="my-4 p-3 bg-light">

                <div>
                    <div class="text-center">
                        
                    </div>
                    <br />
                    <h4><i class="fa fa-key"></i> Login </h4>
                    <hr />
                    <?php $this :: display_page_errors(); ?>

                    <form name="loginForm" action="<?php print_link('main/login'); ?>" class="needs-validation" novalidate method="post">
                        <div class="input-group form-group">
                            <input placeholder="Username..." v-model="user.username" name="username"  required="required" class="form-control" type="text"  />
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="form-control-feedback fa fa-user"></i></span>
                            </div>
                        </div>

                        <div class="input-group form-group">
                            <input  placeholder="Password..." required="required" v-model="user.password" name="password" class="form-control" type="password" />
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="form-control-feedback fa fa-key"></i></span>
                            </div>
                        </div>
                        <div class="row clearfix mt-3 mb-3">

                            <div class="col-6">
                                <label class="">
                                    <input value="true" type="checkbox" name="rememberme" />
                                    Remember Me
                                </label>
                            </div>

                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-primary btn-block btn-md" type="submit"> 
                                <i class="load-indicator">
                                    <clip-loader :loading="loading" color="#fff" size="20px"></clip-loader> 
                                </i>
                                Login
                            </button>
                        </div>
                        <hr />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
