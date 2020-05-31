<?php
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
?>

<section class="page">
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a href="<?php print_link("dataprovider/add") ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fa fa-plus fa-sm text-white-50"></i> New Data Provider</a>
        </div>
    </div>
    <div  class="">
        <div class="container-fluid">

            <div class="row ">

                <div class="col-md-12 comp-grid">

                    <div  class="card animated fadeIn">
                        <div id="users-list-records">
                            <?php $this :: display_page_errors(); ?>


                            <?php
                            if (!empty($records)) {
                                ?>
                                <div class="page-records table-responsive">
                                    <table class="table  table-striped table-sm">
                                        <thead class="table-header bg-light">
                                            <tr>
                                                <th class="td-sno">#</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Created</th>
                                                <th class="td-btn"></th>
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
                                                    <td> 
                                                        <a href="<?php print_link('dataprovider/add/' . $data['data_provider_id']); ?>">
                                                            <?php echo $data['provider_name']; ?> 
                                                        </a>
                                                    </td>
                                                    <td> <?php echo $data['description']; ?> </td>
                                                    <td> <?php echo $data['date_created']; ?> </td>
                                                    <th class="td-btn">
                                                        <a class="btn btn-sm btn-info has-tooltip" title="<?php print_lang('btn_edit_tooltip'); ?>" href="<?php print_link('dataprovider/add/' . $data['data_provider_id']); ?>">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        <a class="btn btn-sm btn-danger recordDeletePromptAction has-tooltip" title="<?php print_lang('btn_delete_tooltip'); ?>" href="<?php print_link("dataprovider/delete/$data[data_provider_id]"); ?>" >
                                                            <i class="fa fa-times"></i>
                                                            Delete
                                                        </a>
                                                    </th>
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
    </div>

</section>
