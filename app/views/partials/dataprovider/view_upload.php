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
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <?php
                if (in_array($view_data['status'], array(DataUploadStatus::draft))) {
                    echo '<a  class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="' . get_link('dataprovider/upload/' . $view_data['data_upload_id']) . '">
                                <i class="fa fa-edit"></i>                              
                                Edit
                           </a>';
                }

                if (in_array($view_data['status'], array(DataUploadStatus::rejected))) {
                    echo '<a  class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="' . get_link('dataprovider/upload/' . $view_data['data_upload_id']) . '">
                                <i class="fa fa-plus"></i>                              
                                Re-upload
                           </a>';
                }
            ?>
        </div>
    </div>
    <div  class="">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <div  class="card animated fadeIn">
                        <?php
                            $this :: display_page_errors();
                        ?>
                        <div class="card-body">
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
                            <h3 class="text-muted">Current Version</h3>
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
                                        <strong>File</strong>
                                    </td>
                                    <td>
                                        <a target="_blank" href="<?= get_link(UPLOAD_FILE_DATAPROVIDER_DIR . $view_data['csv_files']) ?>"><?= $view_data['file_original_name'] ?> <i class="fa fa-download fa-2x"></i></a> 
                                    </td>
                                </tr>
                            </table>
                            <div class="mb-4"></div>
                            <?php
                                $existing = $db->rawQuery("select * from provider_data_upload_history where provider_data_upload_id = {$view_data['data_upload_id']} order by version desc");
                            
                                if ($existing) {
                                    echo '<h3 class="text-muted">Older Versions</h3>';
                                    
                                    echo '<table class="table  table-striped table-sm">
                                            <thead class="table-header bg-light">
                                                <tr>
                                                    <th>Version</th>
                                                    <th>Date</th>
                                                    <th>Comments</th>
                                                    <th>Files</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                    
                                    foreach ($existing as $old_row) {
                                        echo '<tr>';
                                        echo '<td>';
                                        echo $old_row['version'];
                                        echo '</td>';
                                        echo '<td>';
                                        echo $old_row['upload_time'];
                                        echo '</td>';
                                        echo '<td>';
                                        echo $old_row['version_comments'];
                                        echo '</td>';
                                        echo '<td>';
                                        echo '<a href="' . get_link(UPLOAD_FILE_DATAPROVIDER_DIR . $old_row['file_name']) . '">', $old_row['file_original_name'], ' <i class="fa fa-download"></i></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                }
                                
                                echo '<tbody></table>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>