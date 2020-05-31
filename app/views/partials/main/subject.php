<?php
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" ng-controller="subjectCtrl">
    <?= Html::back_button(); ?>
    <div  class="">
        <div class="container-fluid">
            <form id="users-add-form" role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation ajax"  novalidate action="<?php print_link("main/subject"  . (!empty(Router::$page_id) ? "/" . Router::$page_id : "")) ?>" method="post">
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
                                
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="name">Description</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <textarea id="description" name="description" class="form-control" placeholder="Description..."><?= $this->set_field_value('description', ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="name">Grades</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="grade_start" id="grade_start" value="<?= $this->set_field_value('grade_start', ''); ?>" placeholder="From..." class="form-control " />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="grade_stop" id="grade_stop" value="<?= $this->set_field_value('grade_stop', ''); ?>" placeholder="To..." class="form-control " />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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