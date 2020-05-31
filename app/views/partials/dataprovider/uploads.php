<?php
$comp_model = new SharedController;

//Page Data From Controller
$view_data = $this->view_data;

$draft = $view_data->draft;
$record_count = $view_data->draft_count;
$total_records = $view_data->draft_records;


$submitted = $view_data->submitted;
$submitted_record_count = $view_data->submitted_count;
$submitted_total_records = $view_data->submitted_records;

$reviewed = $view_data->reviewed;
$reviewed_record_count = $view_data->reviewed_count;
$reviewed_total_records = $view_data->reviewed_records;

$rejected = $view_data->rejected;
$rejected_record_count = $view_data->rejected_count;
$rejected_total_records = $view_data->rejected_records;

$field_name = Router :: $field_name;
$field_value = Router :: $field_value;

$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;

$db = PDODb::getInstance();
?>

<section class="page">
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a href="<?php print_link("dataprovider/upload") ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fa fa-plus fa-sm text-white-50"></i> New Upload</a>
        </div>
    </div>
    <div  class="">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <div  class="animated fadeIn">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Draft Uploads</h5>
                                <?php
                                    if (!empty($draft)) {
                                        ?>
                                        <div class="page-records table-responsive">
                                            <table class="table  table-striped table-sm">
                                                <thead class="table-header bg-light">
                                                    <tr>
                                                        <th class="td-sno">#</th>
                                                        <th style="width: 20%;">Date</th>
                                                        <th style="width: 20%">Indicator</th>
                                                        <th>File</th>
                                                        <th style="width: 10%;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $counter = 0;
                                                    foreach ($draft as $data) {
                                                        $counter++;
                                                        ?>
                                                        <tr>
                                                            <th class="td-sno"><?php echo $counter; ?></th>
                                                            <td><?= $data['upload_time'] ?></td>
                                                            <td><?= $data['indicator_ref'] ?></td>
                                                            <td><?= $data['file_original_name'] ?></td>
                                                            <td style="text-align: right;">
                                                                <a class="btn btn-sm btn-primary" href="<?php print_link("dataprovider/view_upload/{$data['data_upload_id']}"); ?>" >
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                <a class="btn btn-sm btn-danger recordDeletePromptAction has-tooltip" title="<?php print_lang('btn_delete_tooltip'); ?>" href="<?php print_link("dataprovider/delete_upload/{$data['data_upload_id']}"); ?>" >
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="text-muted animated bounce">
                                            <h4><i class="fa fa-ban"></i> No draft data uploads.</h4>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Submitted Uploads <small>(under review)</small></h5>
                                <?php
                                    if (!empty($submitted)) {
                                        ?>
                                        <div class="page-records table-responsive">
                                            <table class="table  table-striped table-sm">
                                                <thead class="table-header bg-light">
                                                    <tr>
                                                        <th class="td-sno">#</th>
                                                        <th style="width: 20%;">Date</th>
                                                        <th style="width: 20%">Indicator</th>
                                                        <th>File</th>
                                                        <th style="width: 10%;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $counter = 0;
                                                    foreach ($submitted as $data) {
                                                        $counter++;
                                                        ?>
                                                        <tr>
                                                            <th class="td-sno"><?php echo $counter; ?></th>
                                                            <td><?= $data['upload_time'] ?></td>
                                                            <td><?= $data['indicator_ref'] ?></td>
                                                            <td><?= $data['file_original_name'] ?></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="text-muted animated bounce">
                                            <h4><i class="fa fa-ban"></i> No submitted data uploads.</h4>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Rejected/Returned Uploads</h5>
                                <?php
                                    if (!empty($rejected)) {
                                        ?>
                                        <div class="page-records table-responsive">
                                            <table class="table  table-striped table-sm">
                                                <thead class="table-header bg-light">
                                                    <tr>
                                                        <th class="td-sno">#</th>
                                                        <th style="width: 20%;">Date</th>
                                                        <th style="width: 20%">Indicator</th>
                                                        <th>File</th>
                                                        <th style="width: 10%;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $counter = 0;
                                                    foreach ($rejected as $data) {
                                                        $counter++;
                                                        ?>
                                                        <tr>
                                                            <th class="td-sno"><?php echo $counter; ?></th>
                                                            <td><?= $data['upload_time'] ?></td>
                                                            <td><?= $data['indicator_ref'] ?></td>
                                                            <td><?= $data['file_original_name'] ?></td>
                                                            <td style="text-align: right;">
                                                                <a class="btn btn-sm btn-primary" href="<?php print_link("dataprovider/view_upload/{$data['data_upload_id']}"); ?>" >
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                <a class="btn btn-sm btn-danger recordDeletePromptAction has-tooltip" title="<?php print_lang('btn_delete_tooltip'); ?>" href="<?php print_link("dataprovider/delete_upload/{$data['data_upload_id']}"); ?>" >
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="text-muted animated bounce">
                                            <h4><i class="fa fa-ban"></i> No data uploads returned for ammendments.</h4>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Approved Uploads</h5>
                                <?php
                                    if (!empty($reviewed)) {
                                        ?>
                                        <div class="page-records table-responsive">
                                            <table class="table  table-striped table-sm">
                                                <thead class="table-header bg-light">
                                                    <tr>
                                                        <th class="td-sno">#</th>
                                                        <th style="width: 20%;">Date</th>
                                                        <th style="width: 20%">Indicator</th>
                                                        <th>File</th>
                                                        <th style="width: 10%;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $counter = 0;
                                                    foreach ($reviewed as $data) {
                                                        $counter++;
                                                        ?>
                                                        <tr>
                                                            <th class="td-sno"><?php echo $counter; ?></th>
                                                            <td><?= $data['upload_time'] ?></td>
                                                            <td><?= $data['indicator_ref'] ?></td>
                                                            <td><?= $data['file_original_name'] ?></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="text-muted animated bounce">
                                            <h4><i class="fa fa-ban"></i> No reviewed data uploads.</h4>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>