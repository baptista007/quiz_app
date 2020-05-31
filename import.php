<?php

require('config.php');
require MODELS_DIR . "PDODb.php";

$db = PDODb::getInstance();

//
//if (($handle = fopen("mohs.csv", "r")) !== FALSE) {
//    $flag = true;
//    
//    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
//        if ($flag) {$flag = false; continue; }
//        
//        $insert_data = array();
//        
//        $insert_data['indicator_id'] = $data[0];
//        $insert_data['goal_number'] = explode(".", $data[0])[0];
//        $insert_data['indicator_name'] = $data[1];
//        $insert_data['description'] = $data[1];
//        $insert_data['local_indicator_definition'] = $data[3];
//        $insert_data['supports_gender'] = $data[4] == 'x';
//        $insert_data['supports_region'] = $data[5] == 'x';
//        $insert_data['supports_constituency'] = $data[6] == 'x';
//        
//        if (!empty($data[7]) && is_numeric($data[7])) {
//            $insert_data['frequency'] = $data[7];
//        }
//        
//        $row = $db->rawQueryOne("select * from indicator where indicator_id = '" . $data[0] . "' ");
//        
//        if (!$row) {
//            if ($db->insert('indicator', $insert_data)) {
//                echo $data[0], ' inserted <br/>';
//            }
//        } else {
//            $db->where('indicator_id', $data[0]);
//            
//            if ($db->update('indicator', $insert_data)) {
//                echo $data[0], ' updated <br/>';
//            }
//        }
//    }
//
//    fclose($handle);
//}
//
//return;
//
//
//if (($handle = fopen("targets.csv", "r")) !== FALSE) {
//    $flag = true;
//    
//    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
//        if ($flag) {$flag = false; continue; }
//        
//        $insert_data = array();
//        
//        $insert_data['target_id'] = $data[0];
//        $insert_data['goal_number'] = $data[1];
//        $insert_data['description'] = $data[2];
////        $insert_data['date_created'] = $data[3];
////        $insert_data['date_modified'] = $data[4];
//        $insert_data['target_name'] = $data[5];
//        $insert_data['icon'] = $data[6];
////        $insert_data['supports_constituency'] = $data[6] == 'x';
//        
//        
//        $row = $db->rawQueryOne("select * from target where target_id = '" . $data[0] . "' ");
//        
//        if (!$row) {
//            if ($db->insert('target', $insert_data)) {
//                echo $data[0], ' inserted <br/>';
//            } else {
//                echo $db->getLastError(), '<br />';
//            }
//        } else {
//            $db->where('target_id', $data[0]);
//            
//            if ($db->update('target', $insert_data)) {
//                echo $data[0], ' updated <br/>';
//            } else {
//                echo $db->getLastError(), '<br />';
//            }
//        }
//    }
//
//    fclose($handle);
//}
//
//return;

//if (($handle = fopen("goal_1_indicator_1-4-2.csv", "r")) !== FALSE) {
//    $flag = true;
//    
//    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
//        if ($flag) {$flag = false; continue; }
//        
//        $insert_data = array();
//        
//        $insert_data['indicator_id'] = 4;
//        $insert_data['year'] = $data[1];
//        $insert_data['unit_of_measurement'] = $data[2];
////        $insert_data['total'] = $data[3];
//        $insert_data['disaggregation_level'] = $data[3];        
////        $insert_data['ndp_5_target'] = $data[5];
////        $insert_data['region'] = $data[6];
//        $insert_data['value'] = $data[4];
////        $insert_data['\"group\"'] = $data[4] == 'x';
//        $insert_data['indicator_ref_id'] = $data[0];
//        $insert_data['date_created'] = date('Y-m-d H:i:s');
//        $insert_data['date_modified'] = date('Y-m-d H:i:s');
//        
////        $row = $db->rawQueryOne("select * from indicator where indicator_id = '" . $data[0] . "' ");
//        
////        if (!$row) {
//            if ($db->insert('indicator_data', $insert_data)) {
//                echo $data[0], ' inserted <br/>';
//            }
////        } else {
////            $db->where('indicator_id', $data[0]);
////            
////            if ($db->update('indicator', $insert_data)) {
////                echo $data[0], ' updated <br/>';
////            }
////        }
//    }
//
//    fclose($handle);
//}
//
//return;


//$array = array();
//$array[] = array(
//    'enum_id' => Module::upload_provider_data,
//    'name' => 'Upload Data for Provider'
//);
//
//$array[] = array(
//    'enum_id' => Module::upload_data_for_publishing,
//    'name' => 'Upload Data for Publishing'
//);
//
//$array[] = array(
//    'enum_id' => Module::verify_provider_data,
//    'name' => 'Verify Provider Data'
//);
//
//$array[] = array(
//    'enum_id' => Module::verify_data_for_publishing,
//    'name' => 'Verify Data for Publishing'
//);
//
//$array[] = array(
//    'enum_id' => Module::approve_data_for_publishing,
//    'name' => 'Approve Data for Publishing'
//);
//
//$array[] = array(
//    'enum_id' => Module::publish_data,
//    'name' => 'Publish Data'
//);
//
//$array[] = array(
//    'enum_id' => Module::manage_data_providers,
//    'name' => 'Manage Data Providers'
//);
//
//$array[] = array(
//    'enum_id' => Module::manage_users,
//    'name' => 'Manage Users'
//);
//
//foreach ($array as $array) {
//    $row = $db->rawQueryOne("select * from `user_module` where `enum_id` = {$array['enum_id']} ");
//    
//    if (!$row) {
//        if (!$db->insert('user_module', $array)) {
//            echo $db->getLastError(), '<br />';
//        } else {
//            echo $array['name'], ' inserted.<br />';
//        }
//    } else {
//        $db->where("enum_id", $array['enum_id']);
//        
//        if (!$db->update('user_module', $array)) {
//            echo $db->getLastError(), '<br />';
//        } else {
//            echo $array['name'], ' updated.<br />';
//        }
//    }
//}
//
//return;


/*
 Import indicators
 */

if (($handle = fopen("indicators.csv", "r")) !== FALSE) {
    $flag = true;
    
    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
        if ($flag) {$flag = false; continue; }
        
        $insert_data = array();
        
        $insert_data['goal_number'] = $data[0];
        $insert_data['target_id'] = $data[1];
        $insert_data['indicator_id'] = $data[2];
        $insert_data['indicator_name'] = $data[3];
        $insert_data['description'] = $data[3];
        //$insert_data['local_indicator_definition'] = $data[3];
        $insert_data['supports_gender'] = false;
        $insert_data['supports_region'] = false;
        $insert_data['supports_constituency'] = false;
        
        
        $row = $db->rawQueryOne("select * from indicator where indicator_id = '" . $data[2] . "' ");
        
        if (!$row) {
            if ($db->insert('indicator', $insert_data)) {
                echo $data[2], ' inserted <br/>';
            }
        } else {
            $db->where('indicator_id', $data[2]);
            
            if ($db->update('indicator', $insert_data)) {
                echo $data[2], ' updated <br/>';
            }
        }
    }

    fclose($handle);
}

/*
 Import existing data
 */
$nsa_folder = dirname(__FILE__) . '/data/nsa/data';
$giz_folder = dirname(__FILE__) . '/data/giz/data';

$errors = array();
$files = array_diff(scandir($giz_folder), array('.', '..'));

$db->where('stop is null');
$current_template = $db->getOne('data_upload_template');
$db->clearWhere();

foreach ($files as $file) {
    $pieces = explode("_", $file);
    $pieces = explode(".", $pieces[1])[0];
    $ind = str_replace("-", ".", $pieces);

    $db->where("indicator_id = '$ind'");
    $indicator = $db->getOne('indicator');

    if (!$indicator) {
        echo 'Cound not find indicator for ' . $ind . '. Skipping...<br />';
        continue;
    }
    
    $ind_insert = array(
        'data_provider_id' => 4,
        'indicator_id' => $indicator['id'],
        'status' => DataUploadStatus::approved,
        'file_original_name' => $file,
        'upload_time' => date('Y-m-d H:i:s')
    );
    
    $id = $db->insert('data_upload', $ind_insert);

    if (($handle = fopen($giz_folder . DIRECTORY_SEPARATOR . $file, "r")) !== FALSE) {
        $header = false;
        while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
            if (!$header) {
                $header = true;
                continue;
            }

            $idata = array(
                'indicator_id' => $indicator['id'],
                'indicator_ref_id' => $indicator['indicator_id'],
                'upload_id' => $id
            );

            $ykey = $current_template['year'] - 1;
            if (array_key_exists($ykey, $row) && is_numeric($row[$ykey])) {
                $idata['year'] = $row[$ykey];
            }

            $ykey = $current_template['units'] - 1;
            if (array_key_exists($ykey, $row)) {
                $idata['unit_of_measurement'] = $row[$ykey];
            }

            $ykey = $current_template['dis_level'] - 1;
            if (array_key_exists($ykey, $row)) {
                $idata['disaggregation_level'] = $row[$ykey];
            }

            $ykey = $current_template['region'] - 1;
            if (array_key_exists($ykey, $row)) {
                $idata['region'] = $row[$ykey];
            }

            $ykey = $current_template['geocode'] - 1;
            if (array_key_exists($ykey, $row)) {
                $idata['geo_code'] = $row[$ykey];
            }

            $ykey = $current_template['total'] - 1;

            if (array_key_exists($ykey, $row)) {
                $idata['total'] = $row[$ykey];
            }

            $ykey = $current_template['value'] - 1;
            if (array_key_exists($ykey, $row) && is_numeric($row[$ykey])) {
                $idata['value'] = $row[$ykey];
            }

            $query = "select * from indicator_data where indicator_id = {$idata['indicator_id']}";

            if (!empty($idata['region'])) {
                $query .= " and region = '{$idata['region']}'";
            }

            if (!empty($idata['disaggregation_level'])) {
                $query .= " and disaggregation_level = '{$idata['disaggregation_level']}'";
            }

            if (!empty($idata['geo_code'])) {
                $query .= " and geo_code = '{$idata['geo_code']}'";
            }

            if (!empty($idata['year'])) {
                $query .= " and year = {$idata['year']}";
            }

            $ex = $db->rawQueryOne($query);

            try {
                if (!$ex) {
                    if (!$db->insert('indicator_data', $idata)) {
                        $errors[] = $db->getLastError();
                    }
                } else {
                    $db->where("indicator_data_id", $ex['indicator_data_id']);

                    if (!$db->update('indicator_data', $idata)) {
                        $errors[] = $db->getLastError();
                    }
                }
            } catch (Exception $exc) {
                echo $file, '<br />';
                continue;
            }
        }
    }
}