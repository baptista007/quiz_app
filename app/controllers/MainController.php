<?php

class MainController extends SecureController {

    function index() {
        $this->view->render("main/index.php", null, "main_layout.php");
    }

    function login() {
        $this->view->render("main/login.php", null, "login_layout.php");
    }

    function logout() {
        session_destroy();
        $this->view->render("main/login.php", null, "login_layout.php");
    }
    
    function man_quiz() {
        $this->view->page_title = 'Manage Quizzes';
        $this->view->render("main/man_quiz.php", null, "main_layout.php");
    }
    
    function quiz() {
        $this->view->page_title = 'Add/Edit Quiz';
        $this->view->render("main/quiz.php", null, "main_layout.php");
    }
    
    function man_contributor() {
        $this->view->page_title = 'Manage Contributors';
        $this->view->render("main/man_contributor.php", null, "main_layout.php");
    }
    
    function contributor() {
        $this->view->page_title = 'Add/Edit Contributor';
        $this->view->render("main/contributor.php", null, "main_layout.php");
    }
    
    function man_subject($fieldname = null, $fieldvalue = null) {
        $db = PDODb::getInstance();
        
        $limit = $this->get_page_limit(MAX_RECORD_COUNT);
        
        if (!empty($this->orderby)) { // when order by request fields (from $_GET param)
            $db->orderBy($this->orderby, $this->ordertype);
        } else {
            $db->orderBy('name', ORDER_TYPE);
        }
        
        if (!empty($fieldname)) {
            $db->where($fieldname, $fieldvalue);
        }
        
        $tc = $db->withTotalCount();
        $records = $db->get('subject', $limit);
        $data = new stdClass;
        $data->records = $records;
        $data->record_count = count($records);
        $data->total_records = intval($tc->totalCount);
        if ($db->getLastError()) {
            $this->view->page_error = $db->getLastError();
        }
        
        $this->view->page_title = 'Manage Subjects';
        $this->view->render("main/man_subject.php", $data, "main_layout.php");
    }
    
    function subject() {
        $db = PDODb::getInstance();

        if (is_post_request()) {
            $errors = array();
            $modeldata = transform_request_data($_POST);

            $rules_array = array(
                'name' => 'required'
            );

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
            $query = "SELECT * FROM subject WHERE `name` = " . $db->escape($modeldata['name']) . "";

            if (!empty($rec_id)) {
                $query .= " AND id != $rec_id";
            }

            if ($db->rawQuery($query)) {
                $errors[] = "Subject {$modeldata['name']} is already registered with the system.";
            }

            if (empty($errors)) {
                $user_data = array(
                    'name' => $modeldata['name'],
                    'description' => $modeldata['description']
                );
                
                if (!empty($modeldata['grade_start'])) {
                    $user_data['grade_start'] = $modeldata['grade_start'];
                }
                
                if (!empty($modeldata['grade_stop'])) {
                    $user_data['grade_stop'] = $modeldata['grade_stop'];
                }

                if (empty($rec_id)) {
                    $rec_id = $db->insert('subject', $user_data);
                } else {
                    $db->where('id', $rec_id);
                    $bool = $db->update('subject', $user_data);
                }

                //Post save processing
                if (!empty($rec_id) && !$db->getLastError()) {
                    
                } else {
                    if ($db->getLastError()) {
                        $errors[] = $db->getLastError();
                    } else {
                        $errors[] = 'An error occurred while inserting/updating record.';
                    }
                }
            }
            
            ajaxFormPostOutcome($errors, get_link('main/man_subject'), null, 'Subject saved successfully!');
            return;
        }

        if (!empty($rec_id)) {
            $db->where('id', $rec_id);
            $data = $db->getOne('subject');
            $this->view->page_props = $data;
            $this->view->page_title = 'Edit Subject';
        } else {
            $this->view->page_title = 'Add Subject';
        }
        
        $this->view->render("main/subject.php", null, "main_layout.php");
    }

    function get_provider_indicators($provider_id = null) {
        $db = $this->GetModel();

        echo '<option value=""> --select indicator --</option>';

        if (empty($provider_id)) {
            return;
        }

        $query = "SELECT 
                    indicator_data_provider.indicator_id as value,
                    case
                        when indicator.indicator_name != '' OR indicator.indicator_name != NULL then indicator.indicator_name
                    else 
                        indicator.indicator_id
                    end as name,
                    indicator.indicator_id
                FROM indicator_data_provider 
                    INNER JOIN indicator ON indicator.id = indicator_data_provider.indicator_id
                WHERE indicator_data_provider.data_provider_id = " . $provider_id . "
                ORDER BY indicator.indicator_id";
        $provider_indicators = $db->rawQuery($query);

        foreach ($provider_indicators as $indicator) {
            $text = substr($indicator['name'], 0, 100) . (strlen($indicator['name']) > 100 ? '...' : '');
            
            if (!is_numeric($text[0])) 
            {
                $text = $indicator['indicator_id'] . ' ' . $text;
            }
            
            echo '<option value="', $indicator['value'], '" title="', $indicator['name'], '">', $text , '</option>';
        }

        return;
    }

    function delete_upload($rec_ids = null) {
        $db = $this->GetModel();
        $arr_id = explode(',', $rec_ids);
        
        foreach ($arr_id as $rec_id) {
            $db->where('data_upload_id', $rec_id, "=", 'OR');
        }

        $bool = $db->delete('data_upload');

        if ($bool) {
            set_flash_msg('', '');
        } else {
            if ($db->getLastError()) {
                set_flash_msg($db->getLastError(), 'danger');
            } else {
                set_flash_msg('An error occured while deleting recording', 'danger');
            }
        }
        
        redirect_to_page("main/upload");
    }
}
