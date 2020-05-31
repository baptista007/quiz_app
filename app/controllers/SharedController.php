<?php

final class InputType {
    const text = 1;
    const password = 2;
    const submit = 3;
    const hidden = 4;
    const button = 5;
    const checkbox = 6;
    const date = 7;
    const number = 8;
    const radio = 9;
    const select = 10;
    const textarea = 11;
    const datetime = 12;
    const datemonth = 13;
    const color = 14;
    const checkgroup = 15;
    const radiogroup = 16;
    const file = 17;
}

final class StationType {
    const mcs = 1;
    const cts = 2;
    const adcon = 3;
    const other = 99;
}

final class DataLoggerType {
    const campbell = 1;
    const other = 99;
}

final class TransmissionMode {
    const gprs = 1;
    const satellite = 2;
    const manual = 3;
    const other = 99;
}

final class TransferFrequency {
    const min_5 = 1;
    const min_10 = 2;
    const min_15 = 3;
    const min_30 = 4;
    const hour_1 = 5;
    const day_1 = 6;
    const other = 99;
}

final class MaintenanceInterval {
    const monthly = 1;
    const annually = 2;
    const no_maintenance = 3;
    const other = 99;
}

final class Status {
    const active  = 1;
    const inactive = 2;
}

final class YesNo {
    const yes = 1;
    const no = 2;
}

final class HumanFriendValues {
    const supplier = 1;
    const data_logger_type = 2;
    const transmission_mode = 3;
    const transfer_frequency = 4;
    const maintenance_interval = 5;
    const status = 6;
    const yes_no = 7;
    const owner = 8;
}

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends SecureController {
    public static function addInput(
            $name,
            $input_type,
            $value = '',
            $label = '',
            $required = false,
            array $other_attr = array(),
            $source = null,
            $ng_show = null,
            $css_class = 'form-control',
            $style = '',
            $num_decimal = 2,
            $num_right_align = false,
            $default = '-- please select --',
            $echo = true
    ) {
        $html = self::getInput(
                        $name,
                        $input_type,
                        $value,
                        $label,
                        $required,
                        $other_attr,
                        $source,
                        $ng_show,
                        $css_class,
                        $style,
                        $num_decimal,
                        $num_right_align,
                        $default
        );

        if ($echo) {
            echo $html;
            return;
        }

        return $html;
    }

    public static function getInput(
            $name,
            $input_type,
            $value = '',
            $label = '',
            $required = false,
            array $other_attr = array(),
            $source = null,
            $ng_show = null,
            $css_class = 'form-control',
            $style = '',
            $num_decimal = 2,
            $num_right_align = false,
            $default = '-- please select --'
    ) {
        $css_class = " form-control $css_class";

        if ($required && !array_key_exists('required', $other_attr)) {
            $other_attr['required'] = true;
        }

        if (!empty($value)) {
            $ng_value = "";
            
            switch ($input_type) {
                case InputType::checkbox:
                case InputType::radio:
                    $ng_value = "true";
                    break;
                case InputType::date:
                case InputType::datetime:
                    $ng_value = "mysqlDateToJSDate('$value')";
                    break;
                default :
                    $ng_value = "'$value'";
                    break;
            }
            
            $other_attr['ng-init'] = "$name = $ng_value";
        }

        $echo = '<div class="form-group elem-' . $name . '" ' . (!empty($ng_show) ? 'ng-show="' . $ng_show . '"' : '') . '> ';

        if (in_array($input_type, array(InputType::checkbox, InputType::radio))) {
            $echo .= '<div class="checkbox"> ';
            $echo .= '<label>';
            $echo .= self::getSimpleInput(
                            $name,
                            $input_type,
                            $value,
                            $other_attr,
                            $css_class,
                            $style,
                            $num_decimal,
                            $num_right_align
            );

            $echo .= $label;
            $echo .= '</label>';
            $echo .= '</div>';
        } else {
            if (array_key_exists('label', $other_attr)) {
                $label = $other_attr['label'];
            }
            
            if (!empty($label)) {
                $echo .= ' <label for="' . $name . '">';
                $echo .= $label;

                if ($required) {
                    $echo .= ' <span class="required text-danger"> *</span>';
                }

                $echo .= '</label>';
            }

            $echo .= '<div>';
            switch ($input_type) {
                case InputType::select:
                case InputType::checkgroup:
                case InputType::radiogroup:
                    $echo .= self::getOptionsInput(
                                    $name,
                                    $input_type,
                                    $value,
                                    $other_attr,
                                    $source,
                                    $css_class,
                                    $style,
                                    $default
                    );
                    break;
                default :
                    $echo .= self::getSimpleInput(
                                    $name,
                                    $input_type,
                                    $value,
                                    $other_attr,
                                    $css_class,
                                    $style,
                                    $num_decimal,
                                    $num_right_align
                    );
                    break;
            }

            $echo .= '</div>';
        }

        $echo .= '</div>';

        return $echo;
    }

    static function getSimpleInput(
            $name,
            $input_type,
            $value = '',
            array $other_attr = array(),
            $css_class = 'form-control',
            $style = '',
            $num_decimal = 2,
            $num_right_align = false
    ) {

        if ($input_type == InputType::textarea) {
            $echo = '<textarea';
            $echo .= " name=\"$name\" id=\"$name\" ng-model=\"$name\"";
            $echo .= (!empty($css_class) ? ' class="' . $css_class . '"' : '');
            $echo .= (!empty($style) ? ' style="' . $style . '"' : '');
            $echo .= self::getOtherAttrString($other_attr);
            $echo .= '>';
            $echo .= '</textarea>';
            return $echo;
        }

        if ($input_type == InputType::file) {
            $echo = '<input type="file"';
            $echo .= " name=\"$name\" id=\"$name\"";
            $echo .= (!empty($css_class) ? ' class="' . $css_class . '"' : '');
            $echo .= (!empty($style) ? ' style="' . $style . '"' : '');
            $echo .= self::getOtherAttrString($other_attr);
            $echo .= ' />';
            return $echo;
        }

        $echo = '<input type=';
        $echo_js = '';

        switch ($input_type) {
            case InputType::text:
                $echo .= '"text"';
                break;
            case InputType::date:
                $echo .= '"date"';
                break;
            case InputType::number:
                $echo .= '"number"';
                break;
            case InputType::password:
                $echo .= '"password"';
                break;
            case InputType::submit:
                $echo .= '"submit"';
                break;
            case InputType::hidden:
                $echo .= '"hidden"';
                break;
            case InputType::button:
                $echo .= '"button"';
                break;
            case InputType::checkbox:
                $echo .= '"checkbox"';
                break;
            case InputType::radio:
                $echo .= '"radio"';
        }

        $echo .= " name=\"$name\" id=\"$name\" ng-model=\"$name\"";
        $echo .= (!empty($value) ? ' value="' . $value . '"' : '');
        $echo .= (!empty($css_class) && !in_array($input_type, array(InputType::checkbox, InputType::radio)) ? ' class="' . $css_class . '"' : '');
        $echo .= (!empty($style) ? ' style="' . $style . '"' : '');
        $echo .= self::getOtherAttrString($other_attr);
        $echo .= ' />';

        return $echo . $echo_js;
    }

    static function getOptionsInput(
            $name,
            $input_type,
            $value = '',
            array $other_attr = array(),
            $source = null,
            $css_class = 'form-control',
            $style = '',
            $default = '-- please select --'
    ) {
        $echo = '';
        if (!empty($value)) {
            $ng_value = "";
            
            switch ($input_type) {
                case InputType::checkbox:
                case InputType::radio:
                    $ng_value = "true";
                    break;
                case InputType::date:
                case InputType::datetime:
                    $ng_value = "mysqlDateToJSDate('$value')";
                    break;
                default :
                    $ng_value = "'$value'";
                    break;
            }
            
            $other_attr['ng-init'] = "$name = $ng_value";
        }

        switch ($input_type) {
            case InputType::select:
                $echo .= '<select';
                $echo .= " name=\"$name\" id=\"$name\" ng-model=\"$name\"";
                $echo .= (!empty($value) ? ' value="' . $value . '"' : '');
                $echo .= (!empty($css_class) ? ' class="' . $css_class . '"' : '');
                $echo .= (!empty($style) ? ' style="' . $style . '"' : '');
                $echo .= self::getOtherAttrString($other_attr);
                $echo .= '>';

                if (!is_null($default)) {
                    $echo .= '<option value="">';
                    $echo .= $default;
                    $echo .= '</option>';
                }

                if (!empty($source)) {
                    foreach ($source as $option) {
                        $echo .= '<option value="' . (string) $option['value'] . '">';
                        $echo .= $option['label'];
                        $echo .= '</option>';
                    }
                }

                $echo .= '</select>';
                break;
            case InputType::checkgroup:
            case InputType::radiogroup:
                if (!empty($source)) {
                    foreach ($source as $value) {
                        $echo .= '<div class="form-check">
                                <input class="form-check-input" type="' . ($input_type == InputType::checkgroup ? 'checkbox' : 'radio') . '" name="' . $name . '" id="' . $name . '" ng-model="' . $name . '" value="' . $value['value'] . '" ' . self::getOtherAttrString($other_attr) . '>
                                <label class="form-check-label" for="' . $name . '">
                                  ' . $value['name'] . '
                                </label>
                              </div>';
                    }
                }
                break;
        }

        return $echo;
    }

    static function getOtherAttrString(array $other_attrs) {
        $return = "";
        if (!empty($other_attrs)) {
            $options_string = "";

            foreach ($other_attrs as $key => $value) {
                $options_string .= $key . '="' . $value . '"';
            }

            $return = $options_string;
        }

        return $return;
    }

    /**
     * getcount_students Model Action
     * @return Array
     */
    function provider_options_list() {
        $db = $this->GetModel();
        $sqltext = "SELECT data_provider_id as value, provider_name as label, description, date_created FROM public.data_provider;";
        $arr = $db->rawQuery($sqltext);
        return $arr;
    }

    function provider_select_box($name, $value = null, $other_attr = array()) {
        $options = $this->provider_options_list();
        $options[] = array(
            'value' => 99,
            'label' => "Other"
        );
        
        return self::getOptionsInput($name, InputType::select, $value, $other_attr, $options);
    }
    
    function user_select_box($name, $value = null, $other_attr = array()) {
        $options = $this->user_options_list();
//        $options[] = array(
//            'value' => 99,
//            'label' => "New User"
//        );
        
        return self::getOptionsInput($name, InputType::select, $value, $other_attr, $options);
    }
    
    /**
     * @return Array
     */
    function user_options_list() {
        $db = $this->GetModel();
        $sqltext = "SELECT 
                        user_id as value, 
                        CONCAT(name, ' ', surname, ' (', username, ')') as label 
                    FROM public.users 
                    WHERE user_id NOT IN (SELECT primary_data_uploader_id FROM data_provider)
                    AND user_id NOT IN (SELECT secondary_data_uploader_id FROM data_provider);";
        $arr = $db->rawQuery($sqltext);
        return $arr;
    }
    
    /**
     * @return Array
     */
    function goal_options_list() {
        $db = $this->GetModel();
        $sqltext = "SELECT goal_number as value, CONCAT(goal_number, ' - ', goal_name) as label FROM public.goals;";
        $arr = $db->rawQuery($sqltext);
        return $arr;
    }

    function goal_select_box($name, $value = null, $other_attr = array()) {
        $options = $this->goal_options_list();
        
        return self::getOptionsInput($name, InputType::select, $value, $other_attr, $options);
    }

    function station_type_select_box($name, $value, $other_attr = array()) {
        $opts = array();
        $opts[] = array(
            'value' => StationType::mcs,
            'label' => "MCS"
        );

        $opts[] = array(
            'value' => StationType::cts,
            'label' => "CTS"
        );
        
        $opts[] = array(
            'value' => StationType::adcon,
            'label' => "Adcon"
        );
        
        $opts[] = array(
            'value' => StationType::other,
            'label' => "Other"
        );

        return self::getOptionsInput($name, InputType::select, $value, $other_attr, $opts);
    }
    
    function datalogger_type_select_box($name, $value, $other_attr = array()) {
        $opts = array();
        $opts[] = array(
            'value' => DataLoggerType::campbell,
            'label' => "Campbell"
        );
        
        $opts[] = array(
            'value' => DataLoggerType::other,
            'label' => "Other"
        );

        return self::getOptionsInput($name, InputType::select, $value, $other_attr, $opts);
    }
    
    function status_select_box($name, $value, $other_attr = array()) {
        $opts = array();
        $opts[] = array(
            'value' => Status::active,
            'label' => "Active"
        );
        
        $opts[] = array(
            'value' => Status::inactive,
            'label' => "Inactive"
        );
        
        return self::getOptionsInput($name, InputType::select, $value, $other_attr, $opts);
    }
    
    /**
     * @return array json string
     */
    function autocomplete() {
        $db = $this->GetModel();
        $sqltext = "SELECT 
                        user_id as value, 
                        CONCAT(name, ' ', surname, ' (', username, ')') as label 
                    FROM public.users 
                    WHERE user_id in (
                                        SELECT 
                                            uma.user_id 
                                        FROM user_module_access uma 
                                            INNER JOIN user_module um ON uma.user_module_id = um.user_module_id 
                                        WHERE um.enum_id = " . Module::upload_provider_data . "
                                    )
                    AND user_id NOT IN (SELECT primary_data_uploader_id FROM data_provider WHERE primary_data_uploader_id IS NOT NULL)
                    AND user_id NOT IN (SELECT secondary_data_uploader_id FROM data_provider WHERE secondary_data_uploader_id IS NOT NULL)";
        
        if (!empty($_GET['q'])) {
            $search_term = $_GET['q'];
            $sqltext .= " AND (name ILIKE '%$search_term%' OR surname ILIKE '%$search_term%' OR username ILIKE '%$search_term%' )";
        }
        
        $arr = $db->rawQuery($sqltext);
        $row = array();

        foreach ($arr as $r)
        {
            $row[$r['value']] = $r['label'];
        }
        
        echo json_encode($row);
    }
    
    static function fieldNameToHuman($field) {
        return ucwords(str_replace(array('_', '-'), chr(32), $field));
    }
    
    static function alreadyHasFieldNameCombination($array, $key, $ikey) {
        foreach ($array as $arr) {
            if (($key == $arr[0] && $ikey == $arr[1])
                    || ($key == $arr[1] && $ikey == $arr[0])) {
                return true;
            }
        }
        
        return false;
    }
    
    function get_file_preview($filename) {
        $modeldata = transform_request_data($_POST);

        if (!file_exists($_FILES['upload_file'][$filename])) {
            echo 'Invalid file upload.';
        } else {
            if (($handle = fopen($_FILES['upload_file'][$filename], "r")) !== FALSE) {
                $row = 0;

                echo '<table class="table  table-striped table-sm">';

                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $row++;

                    if ($row == 1) {
                        echo '<thead>';
                        echo '<tr>';
                        
                        foreach ($array as $key => $value) {
                            echo '<th>';
                            echo $value;
                            echo '</th>';
                        }
                        
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                    } else {
                        echo '<tr>';
                        
                        foreach ($array as $key => $value) {
                            echo '<td>';
                            echo $value;
                            echo '</td>';
                        }
                        
                        echo '</tr>';
                    }
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo "Failed to open file for previewing.";
            }
        }
        
        return;
    }
    
    function get_file_preview_local() {
        $filename = $_GET['file'];
        
        if (empty($filename)) {
            echo '<div class="alert alert-danger">File path not specified.</div>';
            return;
        }
        
        //$filename = get_link($filename);

        if (!file_exists($filename)) {
            echo '<div class="alert alert-danger">File not found: ' . $filename . '</div>';
        } else {
            if (($handle = fopen($filename, "r")) !== FALSE) {
                $row = 0;

                echo '<table class="table  table-striped table-sm">';

                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $row++;

                    if ($row == 1) {
                        echo '<thead>';
                        echo '<tr>';
                        
                        foreach ($data as $key => $value) {
                            echo '<th>';
                            echo $value;
                            echo '</th>';
                        }
                        
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                    } else {
                        echo '<tr>';
                        
                        foreach ($data as $key => $value) {
                            echo '<td>';
                            echo $value;
                            echo '</td>';
                        }
                        
                        echo '</tr>';
                    }
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="alert alert-danger">Failed to open file for previewing.</div>';
            }
        }
        
        return;
    }
}
