<?php
$comp_model = new SharedController;

//Page Data From Controller
$view_data = $this->view_data;

$field_name = Router :: $field_name;
$field_value = Router :: $field_value;
$id = Router::$page_id;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;

$db = PDODb::getInstance();
?>
<section class="page">
    <?= Html::back_button(); ?>
    <div  class="">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <form role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation ajax"  novalidate action="<?php print_link("main/verify_upload/" . $view_data['data_upload_id']) ?>" method="post" data-feedback-div="div-feedback">
                        <div  class="card animated fadeIn">
                            <div id="div-feedback">
                                <?php
                                $this :: display_page_errors();
                                ?>
                            </div>
                            <div class="card-body">
                                <h3 class="text-muted">Upload Details</h3>
                                <table class="table table-responsive-sm">
                                    <tr>
                                        <td>
                                            <strong>Provider</strong>
                                        </td>
                                        <td>
                                            <?= $view_data['provider_name'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Indicator</strong>
                                        </td>
                                        <td>
                                            <?= $view_data['indicator_id'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Upload Date</strong>
                                        </td>
                                        <td>
                                            <?= $view_data['upload_time'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>View File</strong>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="previewFileGeneral('<?= UPLOAD_FILE_INDICATOR_DIR . $view_data['csv_files'] ?>');">View</button> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Download File</strong>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?= get_link(UPLOAD_FILE_INDICATOR_DIR . $view_data['csv_files']) ?>" class="btn btn-primary btn-sm">Download</a> 
                                        </td>
                                    </tr>
                                </table>
                                <div class="mb-4"></div>
                                <h3 class="text-muted">Review Conclusion</h3>
                                <div class="row p-2">
                                    <div class="col-xs-12 col-lg-6">
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label">Conclusion</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="form-control" name="verify_action" id="verify_action">
                                                        <option value=""> -- select verification conclusion -- </option>
                                                        <option value="1"> Verified </option>
                                                        <option value="2"> Not Verified </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group " id="deny_comment_div" style="display: none;">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label">Comments</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" name="comments" id="comments" placeholder="Comments..." required=""><?= $this->set_field_value('comments') ?></textarea>
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
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(function () {
        $('#verify_action').on('change', function () {
            if ($(this).val() == "2") {
                $('#deny_comment_div').fadeIn();
            } else {
                $('#deny_comment_div').fadeOut();
            }
        });
    });
</script>