<?php

/**
 * Index Page Controller
 * @category  Controller
 */
class IndexController extends SecureController {

    /**
     * Index Action
     * Check If Current Page Is Home Page If Not Redirect to Home Page
     * @return View
     */
    function index() {
        $this->view->render("home/index.php", null, "main_layout.php");
        $this->view->page_title = "Dashboard";
    }
}
