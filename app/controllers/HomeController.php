<?php

/**
 * Home Page Controller
 * @category  Controller
 */
class HomeController extends SecureController {

    /**
     * Index Action
     * @return View
     */
    function index() {
        $this->view->render("home/index.php", null, "main_layout.php");
    }

    function search() {
        $db = $this->GetModel();
        $data = null;
        
        if (!empty($_GET['term'])) {
            $term = $_GET['term'];
            
            $query = "
                    SELECT * FROM 
                    (
                        SELECT
                            1 as type,
                            goal_number as id,
                            concat(goal_number, ' ', goal_name) as name,
                            summary,
                            description
                        FROM goals
                        WHERE CAST(goal_number AS TEXT) like '%$term%' OR goal_name like '%$term%' OR summary like '%$term%' OR description like '%$term%'
                        UNION ALL
                        SELECT
                            2 as type,
                            id,
                            indicator_name as name,
                            NULL as summary,
                            description
                        FROM indicator
                        WHERE indicator_name like '%$term%' OR description like '%$term%'
                        UNION ALL
                        SELECT
                            3 as type,
                            id,
                            target_id as name,
                            NULL as summary,
                            description
                        FROM target
                        WHERE target_id like '%$term%' OR description like '%$term%'
                    ) as data ORDER BY type";
            $data = $db->rawQuery($query);
            
            if (!$data) {
                $this->view->page_error[] = 'The search term "' . $term . '"  did not return any results!';
            }
        } else {
            $this->view->page_error[] = 'Please enter search text';
        }
        
        $this->view->render("home/search.php", $data, "main_layout.php");
    }
}
