<?php
$comp_model = new SharedController;

//Page Data From Controller
$view_data = $this->view_data;

$records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
$field_name = Router :: $field_name;
$field_value = Router :: $field_value;

$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;

$db = PDODb::getInstance();
?>

<section class="page">
    <div  class="">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <div  class="card animated fadeIn">
                        <?php
                        $this :: display_page_errors();
                        
                        if (!empty($records)) {
                            ?>
                            <div class="card">
                                <table class="table  table-striped table-sm">
                                    <thead class="table-header bg-light">
                                        <tr>
                                            <th class="td-sno">#</th>
                                            <th>Uploader</th>
                                            <th>Date</th>
                                            <th>Indicator</th>
                                            <th>Status</th>
                                            <th>Files</th>
                                            <th style="width: 10%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $counter = 0;

                                            foreach ($records as $data) {
                                                $counter++;
                                                ?>
                                                <tr>
                                                    <th class="td-sno"><?php echo $counter; ?></th>
                                                    <td><?= $data['user'] ?></td>
                                                    <td><?= $data['upload_time'] ?></td>
                                                    <td><?= $data['indicator_ref'] ?></td>
                                                    <td><?= getUploadStatusDesc($data['status']) ?></td>
                                                    <td><?= $data['file_original_name'] ?></td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary" href="<?php print_link("main/verify_upload/{$data['data_upload_id']}"); ?>" >
                                                            Review
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
                            Html::no_records();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>