<?php
$comp_model = new SharedController;

//Page Data From Controller
$view_data = $this->view_data;
$field_name = Router :: $field_name;
$field_value = Router :: $field_value;
$provider = $view_data->provider;

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
                    <div id="div-feedback">
                        <?php
                        $this :: display_page_errors();
                        ?>
                    </div>
                    <div  class="card animated fadeIn">
                        <form id="upload-add-form" role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation"  novalidate action="<?php print_link("dataprovider/upload" . (!empty(Router::$page_id) ? '/' . Router::$page_id : '')) ?>" method="post">
                            <?php
                                if (!empty($this->page_props) && $this->page_props['status'] == DataUploadStatus::rejected) {
                                    echo '<div class="alert alert-info">';
                                    
                                    echo '<div class="form-group">';
                                    echo '<h4><i class="fa fa-info"></i> Note: You are performing a re-upload of data.</h4>';
                                    echo '</div>';
                                    
                                    echo '<div class="form-group">';
                                    echo '<label class="control-label">Current Upload:</label> ';
                                    echo '<a href="' . get_link(UPLOAD_FILE_DATAPROVIDER_DIR . $this->page_props['csv_files']) . '">', $this->page_props['file_original_name'], ' <i class="fa fa-download"></i></a>';
                                    echo '</div>';
                                    
                                    if (!empty($this->page_props['version_comments'])) {
                                        echo '<div class="form-group">';
                                        echo '<label class="control-label">Version Comments:</label> ';
                                        echo $this->page_props['version_comments'];
                                        echo '</div>';
                                    }
                                    
                                    echo '</div>';
                                }
                            ?>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="name">Provider</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <strong>
                                                <?= $provider['provider_name'] ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="indicator">Indicator <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <?php
                                                if (empty($this->page_props)) {
                                                    echo '<select name="indicator" id="indicator" class="form-control" required="">';
                                                    echo '<option value=""> -- select indicator -- </option>';

                                                    $selected_indicator = $this->set_field_value('indicator', '');
                                                    $query = "SELECT 
                                                                indicator_data_provider.indicator_id as value,
                                                                indicator.indicator_name as name
                                                            FROM indicator_data_provider 
                                                                INNER JOIN indicator ON indicator.id = indicator_data_provider.indicator_id
                                                            WHERE indicator_data_provider.data_provider_id = " . $provider['data_provider_id'] . " order by indicator.indicator_id ";
                                                    $provider_indicators = $db->rawQuery($query);

                                                    foreach ($provider_indicators as $indicator) {
                                                        echo '<option value="', $indicator['value'], '" title="', $indicator['name'], '" ', ($selected_indicator == $indicator['value'] ? 'selected' : ''), '>', substr($indicator['name'], 0, 100) . (strlen($indicator['name']) > 100 ? '...' : ''), '</option>';
                                                    }

                                                    echo '</select>';
                                                } else {
                                                    echo SharedController::getInput('indicator', InputType::hidden, $this->page_props['indicator_id']);
                                                    echo $this->page_props['indicator_ref'];
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label">Source</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="source" id="source" placeholder="Source..."><?= $this->set_field_value('source') ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label">URL</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="url" id="url" class="form-control" value="<?= $this->set_field_value('url') ?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label">File <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <label class="input-group-btn">
                                                    <span class="btn btn-primary">Browse&hellip;
                                                        <input type="file" style="display: none;" name="upload_file" id="upload_file" multiple=""/>
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uploadProgress"></div>
                                </div>
                                <?php
                                    if (!empty($this->page_props) && $this->page_props['status'] == DataUploadStatus::rejected) {
                                        ?>
                                        <input type="hidden" name="is_reupload" id="is_reupload" value="1" />
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label">Provide comments for the reason of re-uploading <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" name="comments" id="comments" placeholder="Comments..." required=""><?= $this->set_field_value('comments') ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                            <div class="form-group form-submit-btn-holder text-center">
                                <?php
                                    if (empty($this->page_props) || $this->page_props['status'] != DataUploadStatus::rejected) {
                                ?>
                                <button class="btn btn-primary" type="submit" name="save">
                                    Save <i class="fa fa-save"></i>
                                </button>
                                <?php
                                    }
                                ?>
                                
                                <button class="btn btn-primary" type="submit" name="submit" onclick="return confirm('You are about to submit this data upload for review. Are you sure you want to proceed?')">
                                    Submit <i class="fa fa-send"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="preview-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Preview SDG Data - CSV</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5>Please review the values of the CSV before confirming submission.</h5>
                </div>
                <div id="preview-modal-holder"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirm-preview-submit" onclick="closeModal('preview-modal'); $('#upload-add-form').submit(); return false;">Confirm & Submit</button>
                <button type="button" class="btn btn-warning" onclick="closeModal('preview-modal');">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var isSubmit;
    
    // We can attach the `fileselect` event to all file inputs on the page
    $(document).on('change', ':file', function () {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
        $('#upload-add-form').find('#previewing').remove();
    });

    // We can watch for our custom `fileselect` event like this
    $(document).ready(function () {
        $(':file').on('fileselect', function (event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
            }
        });
        
        $(':submit').click(function() {
            isSubmit = $(this).attr('name') == 'submit';
        });

        $('form').ajaxForm({
            delegation: true,
            beforeSubmit: function (formData, jqForm, options) {
                $('#div-feedback').html('');
                
                if (isEmpty($('#upload_file').val())) {
                    $('#div-feedback').html('<div class="alert alert-danger">Select one or more fields to proceed.</div>');
                    return false;
                }
                
                $(jqForm[0]).find('.error.help-block').remove();
                $(jqForm[0]).find('.has-error').removeClass('has-error');

                var $submitButton = $(jqForm[0]).find('input[type=submit]');
                toggleSubmitDisabled($submitButton);
                
                if (isSubmit) {
                    $(jqForm[0]).append("<input type='hidden' name='is_submit' id='is_submit' value='1' />");
                } else {
                    $(jqForm[0]).find('#is_submit').remove();
                }
            },
            uploadProgress: function (event, position, total, percentComplete) {
                $('.uploadProgress').show().html('Uploading Files - ' + percentComplete + '% Complete...    ');
            },
            error: function (data, statusText, xhr, $form) {
                if (422 == data.status) {
                    return;
                }

                var $submitButton = $form.find('input[type=submit]');
                $('.uploadProgress').hide();
            },
            success: function (data, statusText, xhr, $form) {
                var $submitButton = $($form[0]).find('input[type=submit]');
                
                if (!$form.find('#previewing').length) {
                    if (data == '1') {
                        $('#preview-modal-holder').html('<div class="alert alert-danger"></div>');
                    } else if (data == '2') {
                        $('#preview-modal-holder').html('<div class="alert alert-danger"></div>');
                    } else {
                        $form.append("<input type='hidden' name='previewing' id='previewing' value='Previewing...' />");
                        $('#preview-modal-holder').html(data);
                    }
                    
                    $('#preview-modal').modal('show');
                    $('.uploadProgress').hide();
                } else {
                    data = JSON.parse(data);
                    
                    if (data.success) {
                        $('#div-feedback').html('<div class="alert alert-success">' + data.message + '</div>');
                        
                        if (!isEmpty(data.redirectUrl)) {
                            setTimeout(function() {
                                window.location.href = data.redirectUrl;
                            }, 3500);
                        }
                    } else {
                       $('#div-feedback').html('<div class="alert alert-danger">' + data.message + '</div>');
                       toggleSubmitDisabled($submitButton);
                    }

                    $('.uploadProgress').hide();
                }
            }
        });
    });
</script>