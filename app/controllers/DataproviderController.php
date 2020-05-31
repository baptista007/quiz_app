<?php

/**
 * Users Page Controller
 * @category  Controller
 */
class DataproviderController extends SecureController {

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
        $fields = array('data_provider_id', 'provider_name', 'description', 'date_created');
        $limit = $this->get_page_limit(MAX_RECORD_COUNT); // return pagination from BaseModel Class e.g array(5,20)
        if (!empty($this->search)) {
            $text = $this->search;
            $db->orWhere('provider_name', "%$text%", 'LIKE');
            $db->orWhere('description', "%$text%", 'LIKE');
        }
        if (!empty($this->orderby)) { // when order by request fields (from $_GET param)
            $db->orderBy($this->orderby, $this->ordertype);
        } else {
            $db->orderBy('data_provider_id', ORDER_TYPE);
        }
        if (!empty($fieldname)) {
            $db->where($fieldname, $fieldvalue);
        }
        //page filter command
        $tc = $db->withTotalCount();
        $records = $db->get('data_provider', $limit, $fields);
        $data = new stdClass;
        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = intval($tc->totalCount);
        if ($db->getLastError()) {
            $this->view->page_error = $db->getLastError();
        }
        $this->view->page_title = 'Data Providers';
        $this->view->render('dataprovider/list.php', $data, 'main_layout_no_vue.php');
    }

    /**
     * View Record Action 
     * @return View
     */
    function view($rec_id = null, $value = null) {
        $db = $this->GetModel();
        $fields = array('data_provider_id', 'provider_name', 'description', 'date_created');
        if (!empty($value)) {
            $db->where($rec_id, urldecode($value));
        } else {
            $db->where('data_provider_id', $rec_id);
        }
        $record = $db->getOne('data_provider', $fields);
        if (!empty($record)) {
            $this->view->page_title = 'View Data Providers';
            $this->view->render('dataprovider/view.php', $record, 'main_layout_no_vue.php');
        } else {
            if ($db->getLastError()) {
                $this->view->page_error = $db->getLastError();
            } else {
                $this->view->page_error = get_lang('record_not_found');
            }
            $this->view->render('dataprovider/view.php', $record, 'main_layout_no_vue.php');
        }
    }

    /**
     * Add New Record Action 
     * If Not $_POST Request, Display Add Record Form View
     * @return View
     */
    function add($rec_id = null) {
        $db = $this->GetModel();

        if (is_post_request()) {
            $modeldata = transform_request_data($_POST);
            $rules_array = array(
                'provider_name' => 'required',
                'primary_data_uploader' => 'required'
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
            
            if ($modeldata['primary_data_uploader'] == $modeldata['secondary_data_uploader']) {
                $this->view->page_error[] = 'Primary and secondary data uploader can not be the same person.';
            }

            if (empty($this->view->page_error)) {
                $data = array(
                    'provider_name' => $modeldata['provider_name'],
                    'description' => $modeldata['description'],
                    'primary_data_uploader_id' => $modeldata['primary_data_uploader']
                );
                
//                if (!empty($modeldata['secondary_data_uploader'])) {
//                    $data['secondary_data_uploader_id'] = $modeldata['secondary_data_uploader'];
//                }

                if (empty($rec_id)) {
                    $rec_id = $db->insert('data_provider', $data);
                } else {
                    $db->where('data_provider_id', $rec_id);
                    $bool = $db->update('data_provider', $data);

                    if ($bool) {
                        //Delete existing receipt items
                        $db->where('data_provider_id', $rec_id);
                        $db->delete('indicator_data_provider');
                    }
                }

                //Create receipt items
                if (!empty($rec_id) && !$db->getLastError()) {
                    //Save indicators
                    $goals = $db->rawQuery("SELECT * FROM `goals` ORDER BY `goal_number` ASC");

                    foreach ($goals as $goal) {
                        $indicators = $db->rawQuery("SELECT * FROM `indicator` WHERE `goal_number` = {$goal['goal_number']} ORDER BY `goal_number` ASC");

                        foreach ($indicators as $indicator) {
                            if (isset($modeldata['indicator_' . $goal['goal_number'] . '_' . $indicator['id']])) {
                                $idata = array(
                                    'data_provider_id' => $rec_id,
                                    'indicator_id' => $indicator['id']
                                );
                                
                                if (!$db->insert('indicator_data_provider', $idata)) {
                                    $this->view->page_error[] = $db->getLastError();
                                }
                            }
                        }
                    }
                } else {
                    if ($db->getLastError()) {
                        $this->view->page_error[] = $db->getLastError();
                    } else {
                        $this->view->page_error[] = 'An error occurred while inserting/updating record.';
                    }
                }
            }
        }

        if (!empty($rec_id)) {
            $query = "SELECT
                    data_provider.data_provider_id,
                    data_provider.provider_name,
                    data_provider.description,
                    data_provider.date_created,
                    data_provider.primary_data_uploader_id,
                    CONCAT(pu.name, ' ', pu.surname) as primary_data_uploader,
                    CONCAT(su.name, ' ', su.surname) as secondary_data_uploader
                FROM data_provider
                    LEFT JOIN users pu ON pu.id = data_provider.primary_data_uploader_id
                    LEFT JOIN users su ON su.id = data_provider.secondary_data_uploader_id
                WHERE data_provider.data_provider_id = $rec_id";
            $data = $db->rawQueryOne($query);
            $this->view->page_props = $data;
            $this->view->page_title = 'Edit Data Provider';
        } else {
            $this->view->page_title = 'Add Data Provider';
        }

        $this->view->render('dataprovider/add.php', null, 'main_layout_no_vue.php');
    }

    /**
     * Delete Record Action 
     * @return View
     */
    function delete($rec_ids = null) {
        $db = $this->GetModel();
        $arr_id = explode(',', $rec_ids);
        foreach ($arr_id as $rec_id) {
            $db->where('data_provider_id', $rec_id, "=", 'OR');
        }
        $bool = $db->delete('data_provider');
        if ($bool) {
            set_flash_msg('', '');
        } else {
            if ($db->getLastError()) {
                set_flash_msg($db->getLastError(), 'danger');
            } else {
                set_flash_msg(get_lang('error_deleting_the_record_please_make_sure_that_the_record_exit'), 'danger');
            }
        }
        redirect_to_page("dataprovider");
    }
    
    function uploads() {
        $db = $this->GetModel();

        $data = new stdClass;
        $query = "SELECT
                    provider_data_upload.*,
                    data_provider.provider_name,
                    indicator.indicator_id indicator_ref
                FROM provider_data_upload
                    INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                    INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                    INNER JOIN users ON users.id = provider_data_upload.id
                WHERE provider_data_upload.id = " . get_active_user('id') . "
                AND provider_data_upload.status = " . DataUploadStatus::draft;
        $records = $db->rawQuery($query);
        
        $data->draft = $records;
        $data->draft_count = count($records);
        $data->draft_records = count($records);
        
        $query = "SELECT
                    provider_data_upload.*,
                    data_provider.provider_name,
                    indicator.indicator_id indicator_ref
                FROM provider_data_upload
                    INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                    INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                    INNER JOIN users ON users.id = provider_data_upload.id
                WHERE provider_data_upload.id = " . get_active_user('id') . "
                AND provider_data_upload.status = " . DataUploadStatus::submitted;
        $records2 = $db->rawQuery($query);
        $data->submitted = $records2;
        $data->submitted_count = count($records2);
        $data->submitted_records = count($records2);
        
        $query = "SELECT
                    provider_data_upload.*,
                    data_provider.provider_name,
                    indicator.indicator_id indicator_ref
                FROM provider_data_upload
                    INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                    INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                    INNER JOIN users ON users.id = provider_data_upload.id
                WHERE provider_data_upload.id = " . get_active_user('id') . "
                AND provider_data_upload.status = " . DataUploadStatus::reviewed;
        $records3 = $db->rawQuery($query);
        $data->reviewed = $records3;
        $data->reviewed_count = count($records3);
        $data->reviewed_records = count($records3);
        
        $query = "SELECT
                    provider_data_upload.*,
                    data_provider.provider_name,
                    indicator.indicator_id indicator_ref
                FROM provider_data_upload
                    INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                    INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                    INNER JOIN users ON users.id = provider_data_upload.id
                WHERE provider_data_upload.id = " . get_active_user('id') . "
                AND provider_data_upload.status = " . DataUploadStatus::rejected;
        $records4 = $db->rawQuery($query);
        $data->rejected = $records4;
        $data->rejected_count = count($records4);
        $data->rejected_records = count($records4);


        if ($db->getLastError()) {
            $this->view->page_error = $db->getLastError();
        }
        
        $this->view->page_title = 'Provider Data Uploads';
        $this->view->render("dataprovider/uploads.php", $data, "main_layout_no_vue.php");
    }
    
    function upload($rec_id = null) {
        $db = $this->GetModel();
        $db->where('primary_data_uploader_id', get_active_user('id'), "=");
        $db->where('secondary_data_uploader_id', get_active_user('id'), "=", 'OR');
        $provider = $db->getOne('data_provider');
        
        if (!$provider) {
            set_flash_msg(get_lang('Could not find data provider for which this user is eligible to upload data.'), 'danger');
            redirect('admin');
        }
        
        $row = null;
        $errors = array();
        
        if (!empty($rec_id)) {
            $query = "SELECT
                        provider_data_upload.*, 
                        data_provider.provider_name,
                        indicator.indicator_id indicator_ref
                    FROM provider_data_upload
                        INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                        INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                        INNER JOIN users ON users.id = provider_data_upload.id
                    WHERE provider_data_upload.data_upload_id = $rec_id";
            $row = $db->rawQueryOne($query);
        }

        if (is_post_request()) {
            $modeldata = transform_request_data($_POST);

            if (empty($modeldata['previewing'])) { //Display the file in preview mode
                if (!file_exists($_FILES['upload_file']['tmp_name'])) {
                    echo '-1';
                } else {
                    if (($handle = fopen($_FILES['upload_file']['tmp_name'], "r")) !== FALSE) {
                        $row = 0;
                        
                        echo '<table class="table  table-striped table-sm">';
                        $col_cout = -1;    
                        
                        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                            $row++;
                            
                            if ($row == 1) {
                                $col_cout = count(array_keys($data));
                                echo '<tr>';
                                
                                for ($x = 0; $x < $col_cout; $x++) {
                                    echo '<th>';
                                    echo $data[$x];
                                    echo '</th>';
                                }
                                
                                echo '</tr>';
                                
                                continue;
                            }
                            
                            echo '<tr>';
                                
                            for ($x = 0; $x < $col_cout; $x++) {
                                echo '<td>';
                                echo $data[$x];
                                echo '</td>';
                            }

                            echo '</tr>';
                        }
                        
                        echo '</tbody>
                            </table>';
                    } else {
                        echo "-2";
                    }
                }
            } else {
                $rules_array = array(
                    'indicator' => 'required'
                );
                
                $is_reupload = !empty($modeldata['is_reupload']) || ($row && $row['status'] == DataUploadStatus::rejected);
                
                if ($is_reupload) {
                    $rules_array['comments'] = 'required';
                }
                
                if ($is_reupload && !$row) {
                    $errors[] = "Existing record not found";
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
                } else {
                    $query = "SELECT 
                                indicator.indicator_id,
                                indicator.id
                            FROM indicator_data_provider 
                                INNER JOIN indicator ON indicator.id = indicator_data_provider.indicator_id
                            WHERE indicator.id = " . $modeldata['indicator'] . "";
                    $indicator = $db->rawQueryOne($query);

                    if (!$indicator) {
                        $errors[] = 'Invalid indicator.';
                    }
                }

                if (!file_exists($_FILES['upload_file']['tmp_name'])) {
                    $errors[] = 'Invalid file upload.';
                }
                
                if (empty($errors)) {
                    $uploader = new Uploader();

                    $upload_config = array(
                        'maxSize' => 3,
                        'limit' => 1,
                        'extensions' => array('csv'),
                        'uploadDir' => UPLOAD_FILE_DATAPROVIDER_DIR,
                        'required' => false,
                        'returnfullpath' => true,
                        'removeFiles' => true
                    );

                    $upload_data = $uploader->upload($_FILES['upload_file'], $upload_config);

                    if ($upload_data['isComplete']) {
                        $filepaths = $upload_data['data']['metas'];
                    }

                    if ($upload_data['isSuccess']) {
                        $filenames = "";
                        $nice_filename = "";

                        foreach ($filepaths as $file) {
                            !empty($filenames) && $filenames .= ",";
                            $filenames .= $file['name'];
                            $nice_filename .= $file['old_name'];
                        }

                        $upload = array(
                            'data_provider_id' => $provider['data_provider_id'],
                            'indicator_id' => $modeldata['indicator'],
                            'source' => $modeldata['source'],
                            'url' => $modeldata['url'],
                            'upload_time' => date('Y-m-d H:i:s'),
                            'csv_files' => $filenames,
                            'file_original_name' => $nice_filename,
                            'status' => (isset($modeldata['is_submit']) ? DataUploadStatus::submitted : DataUploadStatus::draft),
                            'id' => get_active_user('id')
                        );
                        
                        if (!empty($modeldata['comments'])) {
                            $upload['version_comments'] = $modeldata['comments'];
                        }
                        
                        if (!$is_reupload) {
                            $upload_id = $db->insert('provider_data_upload', $upload);
                        } else {
                            $db->where('data_upload_id', $row['data_upload_id']);
                            $upload_id = $db->update('provider_data_upload', $upload);
                        }
                        
                        if ($upload_id && !$db->getLastError()) {
                            if ($is_reupload) {
                                $existing = $db->rawQuery("select * from provider_data_upload_history where provider_data_upload_id = {$row['data_upload_id']}");
                                
                                
                                //insert into history
                                $history = array(
                                    'provider_data_upload_id' => $row['data_upload_id'],
                                    'file_name' => $row['csv_files'],
                                    'file_original_name' => $row['file_original_name'],
                                    'version' => ($existing ? count($existing) + 1 : 1),
                                    'version_comments' => $row['version_comments'],
                                    'upload_time' => $row['upload_time'],
                                );
                                
                                if (!$db->insert('provider_data_upload_history', $history)) {
                                    $errors[] = $db->getLastError();
                                }
                            }
                        } else {
                            $errors[] = $db->getLastError();
                        }
                    } else {
                        if ($upload_data['hasErrors']) {
                            $upload_errors = $upload_data['errors'];
                            foreach ($upload_errors as $key => $val) {
                                //you can pass upload errors to the view
                                $errors[] = $val[0];
                            }
                        } else {
                            $errors[] = "Upload encountered an error.";
                        }
                    }
                }

                ajaxFormPostOutcome($errors, get_link('dataprovider/uploads'), null, 'Data upload successful');
            }
            
            return;
        }
        
        $data = new stdClass;
        $data->provider = $provider;
        $this->view->page_title = "Upload Data";
        
        if ($row) {
            $this->view->page_props = $row;
            $this->view->page_title = "Re-Upload Data";
        }
        
        $this->view->render("dataprovider/upload.php", $data, "main_layout_no_vue.php");
    }
    
    function view_upload($rec_id) {
        $db = $this->GetModel();
        $query = "SELECT
                    provider_data_upload.*, 
                    data_provider.provider_name,
                    indicator.indicator_id
                FROM provider_data_upload
                    INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                    INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                    INNER JOIN users ON users.id = provider_data_upload.id
                WHERE provider_data_upload.data_upload_id = $rec_id";
        $data = $db->rawQueryOne($query);
        $this->view->page_title = 'View Uploaded Data';
        $this->view->render("dataprovider/view_upload.php", $data, "main_layout_no_vue.php");
    }
    
    function delete_upload($rec_ids = null) {
        $db = $this->GetModel();
        $arr_id = explode(',', $rec_ids);
        
        foreach ($arr_id as $rec_id) {
            $db->where('data_upload_id', $rec_id, "=", 'OR');
        }

        $bool = $db->delete('provider_data_upload');

        if ($bool) {
            set_flash_msg('', '');
        } else {
            if ($db->getLastError()) {
                set_flash_msg($db->getLastError(), 'danger');
            } else {
                set_flash_msg('An error occured while deleting recording', 'danger');
            }
        }
        
        redirect_to_page("dataprovider/uploads");
    }
    
    function verify($rec_id = null) {
        $db = $this->GetModel();
        $query = "SELECT
                    provider_data_upload.*, 
                    data_provider.provider_name,
                    indicator.indicator_id indicator_ref,
                    CONCAT(users.name, ' ', users.surname) as user
                FROM provider_data_upload
                    INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                    INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                    INNER JOIN users ON users.id = provider_data_upload.id
                WHERE provider_data_upload.status = " . DataUploadStatus::submitted;
        $records = $db->rawQuery($query);
        
        $data = new stdClass;
        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = count($records);

        if ($db->getLastError()) {
            $this->view->page_error = $db->getLastError();
        }
        
        $this->view->page_title = 'Verify Data - Providers';
        $this->view->render("dataprovider/verify.php", $data, "main_layout_no_vue.php");
    }
    
    function verified($rec_id = null) {
        $db = $this->GetModel();
        $query = "SELECT
                    provider_data_upload.*, 
                    data_provider.provider_name,
                    indicator.indicator_id indicator_ref,
                    CONCAT(users.name, ' ', users.surname) as user
                FROM provider_data_upload
                    INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                    INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                    INNER JOIN users ON users.id = provider_data_upload.id
                WHERE provider_data_upload.status = " . DataUploadStatus::reviewed;
        $records = $db->rawQuery($query);
        
        $data = new stdClass;
        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = count($records);

        if ($db->getLastError()) {
            $this->view->page_error = $db->getLastError();
        }
        
        $this->view->page_title = 'Verified Provider Data';
        $this->view->render("dataprovider/verified.php", $data, "main_layout_no_vue.php");
    }
    
    function verify_upload($rec_id = null) {
        $db = $this->GetModel();
        $query = "SELECT
                    provider_data_upload.*, 
                    data_provider.provider_name,
                    indicator.indicator_id
                FROM provider_data_upload
                    INNER JOIN data_provider ON data_provider.data_provider_id = provider_data_upload.data_provider_id
                    INNER JOIN indicator ON indicator.id = provider_data_upload.indicator_id
                    INNER JOIN users ON users.id = provider_data_upload.id
                WHERE provider_data_upload.data_upload_id = $rec_id";
        $data = $db->rawQueryOne($query);
        
        
        if (is_post_request()) {
            $modeldata = transform_request_data($_POST);
            $errors = array();
            $rules_array = array(
                'verify_action' => 'required'
            );
            
            if ($modeldata['verify_action'] == '2') {
                $rules_array['comments'] = 'required';
            }

            if (!$data) {
                $errors[] = "Existing record not found";
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
            } else {
                if ($modeldata['verify_action'] == '1') {
                    $udata = array('status' => DataUploadStatus::reviewed, 'reject_comments' => NULL);
                } else {
                    $udata = array('status' => DataUploadStatus::rejected, 'reject_comments' => $modeldata['comments']);
                }
                
                $db->where("data_upload_id", $rec_id);
                
                if (!$db->update('provider_data_upload', $udata)) {
                    $errors[] = $db->getLastError();
                }
            }

            ajaxFormPostOutcome($errors, get_link('dataprovider/uploads'), null, 'Data upload verified successfully.');
            return;
        }
        
        if (!$data) {
            $this->view->page_error[] = "No record with ID $rec_id was found.";
        }
        
        $this->view->page_title = 'Verify Data';
        $this->view->render("dataprovider/verify_upload.php", $data, "main_layout_no_vue.php");
    }
}
