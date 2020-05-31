<?php

/**
 * Users Page Controller
 * @category  Controller
 */
class UsersController extends SecureController {

    /**
     * Load Record Action 
     * $arg1 Field Name
     * $arg2 Field Value 
     * $param $arg1 string
     * $param $arg1 string
     * @return View
     */
    function index($fieldname = null, $fieldvalue = null) {
        $db = $this->GetModel();
        $fields = array('user_id', 'name', 'surname', 'username', 'email', 'password_hash', 'account_status', 'role', 'date_created', 'date_modified');
        $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
        if (!empty($this->search)) {
            $text = $this->search;
            $db->orWhere('user_id', "%$text%", 'LIKE');
            $db->orWhere('name', "%$text%", 'LIKE');
            $db->orWhere('surname', "%$text%", 'LIKE');
            $db->orWhere('username', "%$text%", 'LIKE');
            $db->orWhere('email', "%$text%", 'LIKE');
            $db->orWhere('password_hash', "%$text%", 'LIKE');
            $db->orWhere('account_status', "%$text%", 'LIKE');
            $db->orWhere('role', "%$text%", 'LIKE');
        }
        if (!empty($this->orderby)) { // when order by request fields (from $_GET param)
            $db->orderBy($this->orderby, $this->ordertype);
        } else {
            $db->orderBy('user_id', ORDER_TYPE);
        }
        if (!empty($fieldname)) {
            $db->where($fieldname, $fieldvalue);
        }


        //Exclude admin account from listing
        //$db->where("username", "admin", "!=");
        //page filter command
        $tc = $db->withTotalCount();
        $records = $db->get('users', $limit, $fields);
        $data = new stdClass;
        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = intval($tc->totalCount);
        if ($db->getLastError()) {
            $this->view->page_error = $db->getLastError();
        }
        $this->view->page_title = 'System Users';

        if (!isset($_GET['is_ajax'])) {
            $this->view->render('users/list.php', $data, 'main_layout_no_vue.php');
        } else {
            $this->view->render('users/list.php', $data, 'ajax_layout.php');
        }
    }

    /**
     * View Record Action 
     * @return View
     */
    function view($rec_id = null, $value = null) {
        $db = $this->GetModel();
        $fields = array('id', 'name', 'surname', 'username', 'email', 'user_password_hash', 'user_account_status', 'role', 'user_failed_logins', 'user_last_failed_login', 'created', 'last_modified');
        if (!empty($value)) {
            $db->where($rec_id, urldecode($value));
        } else {
            $db->where('id', $rec_id);
        }
        $record = $db->getOne('tbl_user', $fields);
        if (!empty($record)) {
            $this->view->page_title = get_lang('view_users');
            $this->view->render('users/view.php', $record, 'main_layout_no_vue.php');
        } else {
            if ($db->getLastError()) {
                $this->view->page_error = $db->getLastError();
            } else {
                $this->view->page_error = get_lang('record_not_found');
            }
            $this->view->render('users/view.php', $record, 'main_layout_no_vue.php');
        }
    }

    /**
     * Add New Record Action 
     * If Not $_POST Request, Display Add Record Form View
     * @return View
     */
    function add($rec_id = null) {
        $db = PDODb::getInstance();

        if (is_post_request()) {
            $errors = array();
            $modeldata = transform_request_data($_POST);

            $rules_array = array(
                'name' => 'required',
                'surname' => 'required',
                'username' => 'required',
                'email' => 'required|valid_email'
            );

            //Require password fields if user is new
            if (empty($rec_id)) {
                $rules_array['user_password_hash'] = 'required';
                $rules_array['confirm_password'] = 'required';
            }

            $is_valid = GUMP::is_valid($modeldata, $rules_array);

            if ($is_valid !== true) {
                if (is_array($is_valid)) {
                    foreach ($is_valid as $error_msg) {
                        $errors[] = $error_msg;
                    }
                } else {
                    $errors[] = $is_valid;
                }
            }

            //Validate unique email address
            $query = "SELECT * FROM `users` WHERE `username` = " . $db->escape($modeldata['username']) . "";

            if (!empty($rec_id)) {
                $query .= " AND `user_id` != $rec_id";
            }

            if ($db->rawQuery($query)) {
                $errors[] = "Username {$modeldata['username']} is already registered with the system.";
            }

            //Validate unique email address
            $query = "SELECT * FROM `users` WHERE `email` = " . $db->escape($modeldata['email']) . "";

            if (!empty($rec_id)) {
                $query .= " AND `user_id` != $rec_id";
            }

            if ($db->rawQuery($query)) {
                $errors[] = "The email address {$modeldata['email']} is already registered with the system.";
            }

            //Validate password if user is new or if new password has been supplied
            if (empty($rec_id) || (!empty($modeldata['user_password_hash']) && !empty($modeldata['confirm_password']))) {
                if ($modeldata['confirm_password'] != $modeldata['user_password_hash']) {
                    $errors[] = 'Password and confirm password must be the same.';
                }
            }

            //Validate Modules + Providers
            $modules = $db->rawQuery("SELECT * FROM `user_module`");
            $at_least_one = false;

            foreach ($modules as $module) {
                if (isset($modeldata['mod_' . $module['user_module_id']])) {
                    $at_least_one = true;
                    break;
                }
            }

            if (!$at_least_one) {
                $errors[] = 'At least one access module needs to be granted.';
            }

            if (empty($errors)) {
                $user_data = array(
                    'name' => $modeldata['name'],
                    'surname' => $modeldata['surname'],
                    'username' => $modeldata['username'],
                    'email' => $modeldata['email']
                );


                if (empty($rec_id) || (!empty($modeldata['user_password_hash']) && !empty($modeldata['confirm_password']))) {
                    $user_data['password_hash'] = password_hash($modeldata['user_password_hash'], PASSWORD_DEFAULT);
                }
                
                if (empty($rec_id)) {
                    $rec_id = $db->insert('users', $user_data);
                } else {
                    $db->where('user_id', $rec_id);
                    $bool = $db->update('users', $user_data);

                    if ($bool) {
                        //Delete existing receipt items
                        $db->where('user_id', $rec_id);
                        $db->delete('user_module_access');
                    }
                }

                //Post save processing
                if (!empty($rec_id) && !$db->getLastError()) {
                    //Module access
                    foreach ($modules as $module) {
                        if (isset($modeldata['mod_' . $module['user_module_id']])) {
                            $idata = array(
                                'user_module_id' => $module['user_module_id'],
                                'user_id' => $rec_id
                            );

                            if (!$db->insert('user_module_access', $idata)) {
                                $errors[] = $db->getLastError();
                            }
                        }
                    }
                } else {
                    if ($db->getLastError()) {
                        $errors[] = $db->getLastError();
                    } else {
                        $errors[] = 'An error occurred while inserting/updating record.';
                    }
                }
            }
            
            ajaxFormPostOutcome($errors, get_link('users'), null, 'User saved successfully!');
            return;
        }

        if (!empty($rec_id)) {
            $fields = array('user_id', 'name', 'surname', 'username', 'email', 'password_hash', 'account_status', 'role', 'date_created', 'date_modified');
            $db->where('user_id', $rec_id);
            $data = $db->getOne('users', $fields);
            $this->view->page_props = $data;
            $this->view->page_title = 'Edit User';
        } else {
            $this->view->page_title = 'Add User';
        }

        $this->view->render('users/add.php', null, 'main_layout_no_vue.php');
    }

    /**
     * Edit Record Action 
     * If Not $_POST Request, Display Edit Record Form View
     * @return View
     */
    function edit($rec_id = null) {
        $db = $this->GetModel();
        if (is_post_request()) {
            $modeldata = transform_request_data($_POST);
            $rules_array = array(
                'name' => 'required',
                'surname' => 'required',
                'username' => 'required',
                'email' => 'required|valid_email',
                'user_password_hash' => 'required',
                'user_account_status' => 'required|numeric',
                'role' => 'required|numeric',
                'user_failed_logins' => 'required|numeric',
                'user_last_failed_login' => 'required|numeric',
                'created' => 'required',
                'last_modified' => 'required',
            );
            $is_valid = GUMP::is_valid($modeldata, $rules_array);
            if ($is_valid !== true) {
                if (is_array($is_valid)) {
                    foreach ($is_valid as $error_msg) {
                        $this->view->page_error[] = $error_msg;
                    }
                } else {
                    $this->view->page_error[] = $is_valid;
                }
            }
            if (empty($this->view->page_error)) {
                $db->where('id', $rec_id);
                $bool = $db->update('tbl_user', $modeldata);
                if ($bool) {
                    set_flash_msg('', '');
                    redirect_to_page("users");
                    return;
                } else {
                    $this->view->page_error[] = $db->getLastError();
                }
            }
        }

        $fields = array('id', 'name', 'surname', 'username', 'email', 'user_password_hash', 'user_account_status', 'role', 'user_failed_logins', 'user_last_failed_login', 'created', 'last_modified');
        $db->where('id', $rec_id);
        $data = $db->getOne('tbl_user', $fields);
        $this->view->page_title = get_lang('edit_users');
        if (!empty($data)) {
            $this->view->render('users/edit.php', $data, 'main_layout_no_vue.php');
        } else {
            if ($db->getLastError()) {
                $this->view->page_error[] = $db->getLastError();
            } else {
                $this->view->page_error[] = get_lang('record_not_found');
            }
            $this->view->render('users/edit.php', $data, 'main_layout_no_vue.php');
        }
    }

    /**
     * Delete Record Action 
     * @return View
     */
    function delete($rec_ids = null) {
        $db = $this->GetModel();
        $arr_id = explode(',', $rec_ids);

        foreach ($arr_id as $rec_id) {
            $db->where('user_id', $rec_id, "=", 'OR');
        }

        $bool = $db->delete('user_module_access');
        $bool = $db->delete('user_data_provider');
        $bool = $db->delete('users');

        if ($bool) {
            set_flash_msg('User deleted successfully!', '');
        } else {
            if ($db->getLastError()) {
                set_flash_msg($db->getLastError(), 'danger');
            } else {
                set_flash_msg(get_lang('error_deleting_the_record_please_make_sure_that_the_record_exit'), 'danger');
            }
        }
        redirect_to_page("users");
    }

}
