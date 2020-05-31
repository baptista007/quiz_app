<?php
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;

$db = PDODb::getInstance();
$subjects = $db->rawQuery("SELECT * FROM subject");

if (!empty(Router::$page_id)) {
    $query = "select
                user_module_id
            from user_module_access
            where id = " . Router::$page_id;
    $checked = $db->rawQueryValue($query);
    empty($checked) && $checked = array();

    $query = "select
                data_provider_id
            from user_data_provider
            where id = " . Router::$page_id;
    $user_providers = $db->rawQueryValue($query);
    empty($user_providers) && $user_providers = array();
} else {
    $checked = array();
    $user_providers = array();
}

?>
<section class="page" ng-controller="quizCtrl">
    <?= Html::back_button(); ?>
    <div  class="">
        <div class="container-fluid">
            <form id="users-add-form" role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation ajax"  novalidate action="<?php print_link("main/quiz"  . (!empty(Router::$page_id) ? "/" . Router::$page_id : "")) ?>" method="post">
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
                                                <input  id="name" value="<?php echo $this->set_field_value('name', ''); ?>" type="text" placeholder="Quiz name..."  required="" name="name" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="subject">Subject <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <?php
                                                    echo '<select name="indicator" id="indicator" class="form-control" required="">';
                                                    echo '<option value=""> -- select indicator -- </option>';

                                                    $selected_subject = $this->set_field_value('subject_id', '');
                                                    
                                                    foreach ($subjects as $subject) {
                                                        echo '<option value="', $subject['id'], '" title="', $subject['name'], '" ', ($selected_subject == $subject['id'] ? 'selected' : ''), '>', substr($subject['name'], 0, 100) . (strlen($subject['name']) > 100 ? '...' : ''), '</option>';
                                                    }

                                                    echo '</select>';
                                                ?>
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
                            </div>
                        </div>
                        
                        <div>
                            <div class="alert lead text-muted">
                                <strong>
                                    Questions
                                </strong>
                            </div>

                            <div class="form-group card" ng-repeat="item in questions">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>
                                            Question
                                        </label>
                                        <div>
                                            <input type="text" ng-model="item.question" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Answers</label>
                                        <div class="input-group mb-3" ng-repeat="(key, option) in item.options">
                                            <div class="input-group-prepend">
                                                {{option}}
                                            </div>
                                            <input type="text" ng-model="option.value" class="form-control" />
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger" ng-click="removeOption($index)">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success" ng-click="addOption($index)">Add</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" ng-click="add()">Add</button>
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