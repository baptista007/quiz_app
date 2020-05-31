<?php
//Page Data From Controller
$view_data = $this->view_data;

if ($view_data) {
    $records = $view_data->records;
    $record_count = $view_data->record_count;
    $total_records = $view_data->total_records;
} else {
    $records = null;
    $record_count = null;
    $total_records = null;
}

$field_name = Router :: $field_name;
$field_value = Router :: $field_value;

$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;
?>

<section class="page">
    <?= Html::add_new_button('main/quiz', 'New Quiz') ?>
    <div  class="">
        <div class="container-fluid">

            <div class="row ">

                <div class="col-md-12 comp-grid">

                    <div  class="card animated fadeIn">
                        <div id="users-list-records">
                            <div class="card-body">
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
                                                    <th>Subject</th>
                                                    <th>Number of questions</th>
                                                    <th>Time</th>
                                                    <th>Created</th>
                                                    <th>Last Modified</th>
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
                                                        <td> <?php echo $data['name']; ?> </td>
                                                        <td> <?php echo $data['subject_name']; ?> </td>
                                                        <td> <?php echo $data['question_count']; ?> </td>
                                                        <td> <?php echo $data['time']; ?> </td>
                                                        <td> <?php echo $data['created']; ?> </td>
                                                        <td> <?php echo $data['modified']; ?> </td>
                                                        <th class="td-btn">
                                                            <a class="btn btn-sm btn-info has-tooltip" title="<?php print_lang('btn_edit_tooltip'); ?>" href="<?php print_link('users/add/' . $data['user_id']); ?>">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>
                                                            <a class="btn btn-sm btn-danger recordDeletePromptAction has-tooltip" title="<?php print_lang('btn_delete_tooltip'); ?>" href="<?php print_link("users/delete/$data[user_id]"); ?>" >
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
    </div>
</section>
