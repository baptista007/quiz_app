<?php
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;

$db = PDODb::getInstance();
$modules = $db->rawQuery("SELECT * FROM `user_module`");

if (!empty(Router::$page_id)) {
    $query = "select
                user_module_id
            from user_module_access
            where user_id = " . Router::$page_id;
    $checked = $db->rawQueryValue($query);
    empty($checked) && $checked = array();

    $query = "select
                data_provider_id
            from user_data_provider
            where user_id = " . Router::$page_id;
    $user_providers = $db->rawQueryValue($query);
    empty($user_providers) && $user_providers = array();
} else {
    $checked = array();
    $user_providers = array();
}

?>
<section class="page" ng-controller="userCtrl">
    <?= Html::back_button(); ?>
    <div  class="">
        <div class="container-fluid">
            <form id="users-add-form" role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation ajax"  novalidate action="<?php print_link("users/add"  . (!empty(Router::$page_id) ? "/" . Router::$page_id : "")) ?>" method="post">
                <div  class="card animated fadeIn">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-xs-12 comp-grid">
                                <?php
                                    $this :: display_page_errors();
                                ?>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="name">Name <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="name" value="<?php echo $this->set_field_value('name', ''); ?>" type="text" placeholder="<?php print_lang('students_add_name_placeholder'); ?>"  required="" name="name" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="surname">Surname <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="surname" value="<?php echo $this->set_field_value('surname', ''); ?>" type="text" placeholder="<?php print_lang('students_add_surname_placeholder'); ?>"  required="" name="surname" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="username">Username <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="username" value="<?php echo $this->set_field_value('username', ''); ?>" type="text" placeholder="<?php print_lang('users_add_user_name_placeholder'); ?>"  required="" name="username" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="email">Email Address <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="email" value="<?php echo $this->set_field_value('email', ''); ?>" type="email" placeholder="<?php print_lang('users_add_email_placeholder'); ?>"  required="" name="email" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="user_password_hash">Password<?= (empty(Router::$page_id) ? ' <span class="text-danger">*</span>' : '') ?></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input  id="user_password_hash" value="" type="password" placeholder="Placeholder..."  required="" name="user_password_hash" class="form-control " autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="confirm_password">Confirm Password<?= (empty(Router::$page_id) ? ' <span class="text-danger">*</span>' : '') ?></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password..." />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
//                                    echo '<div class="form-group ">';
//                                    echo '<div class="row mb-2">';
//                                    echo '<div class="col-sm-4">';
//                                    echo '<label class="control-label" for="accesss_module">Access Module<span class="text-danger">*</span></label>';
//                                    echo '</div>';
//                                    echo '<div class="col-sm-8">';
//                                    echo '<select name="accesss_module" id="accesss_module" class="form-control">';
//                                    echo '<option value=""> -- select access module -- </option>';
//                                    
//                                    foreach ($modules as $module) {
//                                        echo '<option value="', $module['user_module_id'], '" title="', $module['name'], '" ', (in_array($module['user_module_id'], $checked) ? 'selected' : ''), '>', $module['name'], '</option>';
//                                    }
//
//                                    echo '</select>';
//                                    echo '</div>';
//                                    echo '</div>';
//                                    echo '</div>';
//                                    echo '</div>';
                                ?>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert lead text-muted">
                                        Check all modules which user should have access to:
                                    </div>
                                </div>
                            </div>
                            <?php
                            $count = 1;

                            echo '<div class="row mb-2">';

                            foreach ($modules as $module) {
                                echo '<div class="col-lg-2">';
                                echo '<input type="checkbox" name="mod_' . $module['user_module_id'] . '" id="mod_' . $module['user_module_id'] . '" ' . (in_array($module['user_module_id'], $checked) || $this->check_radio_is_posted('mod_' . $module['user_module_id'])  ? 'checked' : '') . ' />';
                                echo '<label class="form-check-label" for="mod_', $module['user_module_id'] , '">', $module['name'] , '</label>';
                                echo '</div>';

                                if ($count % 6 == 0) {
                                    echo '</div> <!-- automatic close -->';

                                    if ($count != count($modules)) {
                                        echo '<div class="row mb-2"> <!-- automatic open -->';
                                    }
                                }

                                $count++;
                            }
//                            
                            echo '</div>';
                            ?>
                        </div>
                        <div class="form-group form-submit-btn-holder text-center">
                            <button class="btn btn-primary" type="submit">
                                Submit <i class="fa fa-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>