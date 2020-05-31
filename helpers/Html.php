<?php

/**
 * Html Helper Class
 * Use To Display Customisable Html Page Component
 * @category  View Helper
 */
class Html {

    /**
     * Display Html Head Title
     * Set Title From Url If Present
     * @return Html
     */
    public static function page_title($title = null) {
        //if title is passed to the url parameters then use it. if not used the titleset on the PageLayout View
        if (!empty($_GET['title'])) {
            $title = $_GET['title'];
        }
        ?>
        <title><?php echo $title; ?></title>
        <?php
    }

    /**
     * Display Html Head Meta Tag
     * @return Html
     */
    public static function page_meta($name, $val = null) {
        ?>
        <meta name="<?php echo $name; ?>" content="<?php echo $val ?>" />
        <?php
    }

    /**
     * Link To Css File From Css Dir
     * NB -- Pass only The Css File Nam-- (eg. style.css) 
     * @return Html
     */
    public static function page_css($arg) {
        ?>
        <link rel="stylesheet" href="<?php print_link(CSS_DIR . $arg); ?>" />
        <?php
    }

    /**
     * Link To Js File From JS Dir
     * NB -- Pass only The Js File Name-- (eg. script.js) 
     * @return Html
     */
    public static function page_js($arg) {
        ?>
        <script type="text/javascript" src="<?php print_link(JS_DIR . $arg); ?>"></script>
        <?php
    }

    public static function display_form_errors($formerror) {
        if (!empty($formerror)) {
            if (!is_array($formerror)) {
                ?>
                <div class="alert alert-danger animated shake">
                    <?php echo $formerror; ?>
                </div>
                <?php
            } else {
                ?>
                <script>
                    $(document).ready(function () {
                <?php
                foreach ($formerror as $key => $value) {
                    echo "$('[name=$key]').parent().addClass('has-error').append('<span class=\"help-block\">$value</span>');";
                }
                ?>
                    });
                </script>
                <?php
            }
        }
    }

    /**
     * Display Page Main Footer Components
     * @return Html
     */
    public static function page_footer($args = null) {
        ?>
        <footer class="footer bg-light">
            <div  class="container-fluid text-center">
                <div class="row">
                    <div  class="col-sm-4 text-left">
                        <div class="copyright">All rights reserved. &copy; <?php echo SITE_NAME ?> - <?php echo date('Y') ?></div>
                    </div>
                </div>
            </div>
        </footer>
        <?php
    }

    /**
     * Display html Hyper Link Tag
     * @return Html
     */
    public static function import_form($form_path, $button_text = "", $format_text = "csv, json") {
        ?>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#import-data">
            <i class="fa fa-file"></i> <?php echo $button_text; ?>
        </button>	

        <form method="post" action="<?php print_link($form_path) ?>" enctype="multipart/form-data" id="import-data" class="modal fade" role="dialog" tabindex="-1" data-backdrop="false" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php print_lang('btn_import_data'); ?></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <label><?php print_lang('txt_import_file_msg'); ?> 
                            <input required="required" class="form-control form-control-sm" type="file" name="file" /> </label>
                        <small class="text-muted"><?php print_lang('txt_import_file_type'); ?>(csv , json)</small>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal"><?php print_lang('btn_close_modal'); ?></button>
                        <button type="submit" class="btn btn-primary"><?php print_lang('btn_import_data'); ?></button>
                    </div>
                </div>
            </div>
        </form>
        <?php
    }
    
    public static function add_new_button($link, $text) {
        ?>
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <a href="<?php print_link($link) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fa fa-plus fa-sm text-white-50"></i> <?= $text ?></a>
            </div>
        </div>   
        <?php
    }
    
    public static function back_button() {
        ?>
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <a href="javascript:void(0)" onclick="javascript:history.go(-1)" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fa fa-arrow-left text-white-50"></i> Back</a>
            </div>
        </div>   
        <?php
    }
    
    public static function no_records() {
        ?>
            <div class="text-muted animated bounce">
                <h4><i class="fa fa-ban"></i> No records to show.</h4>
            </div>
        <?php
    }
}
