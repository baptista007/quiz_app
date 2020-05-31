
<?php
$comp_model = new SharedController;

//Page Data Information from Controller
$data = $this->view_data;

//$rec_id = $data['__tableprimarykey'];
$page_id = Router::$page_id; //Page id from url

$view_title = $this->view_title;

$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;
?>

<section class="page">
    <?= Html::back_button(); ?>
    <div  class="">
        <div class="container-fluid">

            <div class="row ">

                <div class="col-md-12 comp-grid">

                    <div  class="card animated fadeIn">
                        <div class="profile-bg">
                            <div class="profile">
                                <div class="d-flex flex-row justify-content-center">
                                    <div class="avatar"><img src="<?php print_link("assets/images/avatar.png") ?>" /> </div>
                                    <h2 class="title"><?php echo $data['username']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div>
                            <table class="table table-hover table-borderless table-striped">
                                <tbody>

                                    <tr>
                                        <th class="title"> <?php print_lang('students_list_name_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['name']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('students_list_surname_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['surname']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('users_list_user_name_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['username']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('users_list_user_email_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['user_email']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('users_list_user_account_status_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['user_account_status']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('users_list_super_user_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['role']; ?> </td>
                                    </tr>

                                    <tr>
                                        <th class="title"> <?php print_lang('users_list_user_failed_logins_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['user_failed_logins']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('users_list_user_last_failed_login_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['user_last_failed_login']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('users_list_created_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['created']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('users_list_last_modified_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['last_modified']; ?> </td>
                                    </tr>


                                    <tr>
                                        <th class="title"> <?php print_lang('students_list_id_title'); ?> :</th>
                                        <td class="value"> <?php echo $data['id']; ?> </td>
                                    </tr>


                                </tbody>    
                            </table>    
                        </div>  
                        <div class="mt-2">

                            <a class="btn btn-sm btn-info"  href="<?php print_link("users/edit/$data[id]"); ?>">
                                <i class="fa fa-edit"></i> <?php print_lang('students_list_empty_record_prompt'); ?>
                            </a>


                            <a class="btn btn-sm btn-danger recordDeletePromptAction"  href="<?php print_link("users/delete/$data[id]"); ?>" >
                                <i class="fa fa-times"></i> <?php print_lang('students_list_empty_record_prompt'); ?>
                            </a>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</section>
