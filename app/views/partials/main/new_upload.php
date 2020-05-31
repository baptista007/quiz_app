<?php
$comp_model = new SharedController;

//Page Data From Controller
$view_data = $this->view_data;
$field_name = Router :: $field_name;
$field_value = Router :: $field_value;

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
                        <form id="upload-publishing-data" role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation"  novalidate action="<?php print_link("main/new_upload") ?>" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="name">Provider <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <?php
                                                $selected_provider = $this->set_field_value('data_provider', '');
                                                $query = "select
                                                                `data_provider`.data_provider_id as value,
                                                                `data_provider`.provider_name as name
                                                            from `user_data_provider`
                                                                inner join `data_provider` on `data_provider`.data_provider_id = `user_data_provider`.data_provider_id
                                                            where id = " . get_active_user('id');
                                                $user_providers = $db->rawQuery($query);


                                                echo '<select name="data_provider" id="data_provider" class="form-control">';
                                                echo '<option value=""> -- select data provider -- </option>';

                                                foreach ($user_providers as $provider) {
                                                    echo '<option value="', $provider['value'], '" ', ($selected_provider == $provider['value'] ? 'selected' : ''), '>', $provider['name'], '</option>';
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
                                            <label class="control-label" for="indicator">Indicator <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <?php
                                                echo '<select name="indicator" id="indicator" class="form-control">';

                                                if (!empty($selected_provider)) {
                                                    $selected_indicator = $this->set_field_value('indicator', '');
                                                    $query = "SELECT 
                                                                indicator_data_provider.indicator_id as value,
                                                                indicator.indicator_name as name
                                                            FROM indicator_data_provider 
                                                                INNER JOIN indicator ON indicator.id = indicator_data_provider.indicator_id
                                                            WHERE indicator_data_provider.data_provider_id = " . $selected_provider . " order by indicator.indicator_id ";
                                                    $provider_indicators = $db->rawQuery($query);

                                                    foreach ($provider_indicators as $indicator) {
                                                        echo '<option value="', $indicator['value'], '" title="', $indicator['name'], '" ', ($selected_indicator == $indicator['value'] ? 'selected' : ''), '>', substr($indicator['name'], 0, 100) . (strlen($indicator['name']) > 100 ? '...' : ''), '</option>';
                                                    }
                                                } else {
                                                    echo '<option value=""> -- select data provider -- </option>';
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
                                            <label class="control-label">Source</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="source" id="source" placeholder="Source..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label">URL</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="url" id="url" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label">Files <span class="text-danger">*</span></label>
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
                            </div>
                            <div class="form-group form-submit-btn-holder text-center">
                                <button class="btn btn-primary" type="submit">
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
                <button type="button" class="btn btn-success" id="confirm-preview-submit" onclick="closeModal('preview-modal'); $('#upload-publishing-data').submit(); return false;">Confirm & Submit</button>
                <button type="button" class="btn btn-warning" onclick="closeModal('preview-modal');">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // We can attach the `fileselect` event to all file inputs on the page
    $(document).on('change', ':file', function () {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    // We can watch for our custom `fileselect` event like this
    $(document).ready(function () {
        $('#data_provider').change(function () {
            $.get('<?= SITE_ADDR ?>main/get_provider_indicators/' + this.value, function (data) {
                $('#indicator').html(data);
            });
        });
        
        $(':file').on('fileselect', function (event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
            }
        });
        
        $('form').ajaxForm({
            delegation: true,
            beforeSubmit: function (formData, jqForm, options) {
                $(jqForm[0]).find('.error.help-block').remove();
                $(jqForm[0]).find('.has-error').removeClass('has-error');

                var $submitButton = $(jqForm[0]).find('input[type=submit]');
                toggleSubmitDisabled($submitButton);
            },
            uploadProgress: function (event, position, total, percentComplete) {
                $('.uploadProgress').show().html('Uploading Files - ' + percentComplete + '% Complete...    ');
            },
            error: function (data, statusText, xhr, $form) {
                if (422 == data.status) {
                    return;
                }

                var $submitButton = $form.find('input[type=submit]');
                toggleSubmitDisabled($submitButton);
                $('.uploadProgress').hide();
            },
            success: function (data, statusText, xhr, $form) {
                if (!$form.find('#previewing').length) {
                    if (data == '1') {
                        $('#preview-modal-holder').html('<div class="alert alert-danger">Invalid file upload.</div>');
                    } else if (data == '2') {
                        $('#preview-modal-holder').html('<div class="alert alert-danger">Failed to open file for previewing.</div>');
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
                        
                        if (isEmpty(data.redirectUrl)) {
                            setTimeout(function() {
                                window.location.href = data.redirectUrl;
                            }, 3500);
                        }
                    } else {
                       $('#div-feedback').html('<div class="alert alert-danger">' + data.message + '</div>');
                       var $submitButton = $form.find('input[type=submit]');
                        toggleSubmitDisabled($submitButton);
                    }

                    $('.uploadProgress').hide();
                }
            }
        });
    });
</script>