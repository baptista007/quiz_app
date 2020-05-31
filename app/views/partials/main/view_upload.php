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
                    <div  class="card animated fadeIn">
                        <?php
                        $this :: display_page_errors();
                        ?>
                        <?php
                        if (in_array($view_data['status'], array(DataUploadStatus::rejected))) {
                            echo '<div class="alert alert-warning">';

                            echo '<div class="form-group">';
                            echo '<h4><i class="fa fa-info"></i> Reject comments:</h4>';
                            echo '</div>';

                            echo $view_data['reject_comments'];
                            echo '</div>';
                        }
                        ?>
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
                                    <strong>Uploader</strong>
                                </td>
                                <td>
                                    <?= $view_data['user'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Status</strong>
                                </td>
                                <td>
                                    <?= getUploadStatusDesc($view_data['status']) ?>
                                </td>
                            </tr>
                            <?php if (!empty($view_data['version_comments'])) { ?>
                                <tr>
                                    <td>
                                        <strong>Comments</strong>
                                    </td>
                                    <td>
                                        <?= $view_data['version_comments'] ?>
                                    </td>
                                </tr>
                            <?php } ?>
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
                                    <a target="_blank" class="btn btn-primary btn-sm" href="<?= get_link(UPLOAD_FILE_INDICATOR_DIR . $view_data['csv_files']) ?>"><?= $view_data['file_original_name'] ?> Download</a> 
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>