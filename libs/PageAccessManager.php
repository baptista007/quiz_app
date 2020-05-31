<?php

/**
 * Role Based Access Control
 * @category  RBAC Helper
 */
defined('ROOT') OR exit('No direct script access allowed');

class PageAccessManager {

    /**
     * Array of user roles and page access 
     * Use "*" to grant all access right to particular user role
     * @return Html View
     */
    public static $usersRolePermissions = '*';

    /**
     * pages to exclude from access validation check
     * @var $excludePageCheck array()
     */
    public static $excludePageCheck = array("", "index", "home", "account", "info", "report");
    
    public static function getPathAccessRequirements($path) {
        $path = strtolower(trim($path, '/'));

        $arrPath = explode("/", $path);
        $page = strtolower($arrPath[0]);
        $action = (!empty($arrPath[1]) ? $arrPath[1] : null);
        if (empty($action)) {
            $action = "index";
        }
        
        $min_access = array();
        
        if ($page == "admin") {
            switch ($action) {
                case "verify":
                    $min_access[] = Module::verify_data_for_publishing;
                    break;
                case "upload":
                case "view_upload":
                case "new_upload":
                case "new_upload_submit":
                case "delete_upload":
                    $min_access[] = Module::upload_data_for_publishing;
                    break;
                case "approve":
                    $min_access[] = Module::approve_data_for_publishing;
                    break;
                case "publish":
                    $min_access[] = Module::publish_data;
                    break;
                case "configuration":
                    $min_access[] = Module::manage_configuration;
                    break;
            }
        }
        
        if ($page == "users") {
            $min_access[] = Module::manage_users;
        }
        
        if ($page == "dataprovider") {
            switch ($action) {
                case "uploads":
                case "upload":
                case "view_upload":
                case "delete_upload":
                    $min_access[] = Module::upload_provider_data;
                    break;
                case "verify":
                    $min_access[] = Module::verify_provider_data;
                    break;
                default :
                    $min_access[] = Module::manage_data_providers;
            }
        }
        
        return $min_access;
    }

    /**
     * Display About us page
     * @return string
     */
    public static function GetPageAccess($path) {
        if (get_active_user("username") ==  "admin") {
            return "AUTHORIZED";
        }
        
        $min_access = self::getPathAccessRequirements($path);
        
        if (empty($min_access)) {
            return "AUTHORIZED";
        } else {
            $db = PDODb::getInstance();
            
            $query = "select
                        m.enum_id 
                    from user_module_access md
                        inner join user_module m on m.user_module_id = md.user_module_id
                    where md.id = " . get_active_user("id");
            $users_access = $db->rawQueryValue($query);
            empty($users_access) && $users_access = array();
            
            $auth = false;
            
            foreach ($min_access as $value) {
                if (in_array($value, $users_access)) {
                    $auth = true;
                }
            }

            if ($auth) {
                return "AUTHORIZED";
            } else {
                return "NOT_AUTHORIZED";
            }
        }
    }

    public static function is_allowed($path) {
        $access = self::GetPageAccess($path);
        return ($access == 'AUTHORIZED');
    }
}

?>