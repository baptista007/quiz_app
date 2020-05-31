<?php
$comp_model = new SharedController;

$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
Router::$field_value;

$db = PDODb::getInstance();
$goals = $db->rawQuery("SELECT * FROM `goals` ORDER BY `goal_number` ASC");
?>
<section class="page" ng-controller="dataProviderCtrl">
    <?= Html::back_button(); ?>
    <form id="users-add-form" role="form" enctype="multipart/form-data" class="form form-horizontal needs-validation"  novalidate action="<?php print_link("dataprovider/add" . (!empty(Router::$page_id) ? "/" . Router::$page_id : "")) ?>" method="post">
        <div class="container-fluid">
            <div  class="card animated fadeIn">
                <?php
                    $this :: display_page_errors();
                ?>
                <div class="row">
                    <div class="col-lg-6 col-xs-12 comp-grid">
                        <div class="card-body">
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="control-label" for="name">Name <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="">
                                            <input  id="provider_name" value="<?php echo $this->set_field_value('provider_name', ''); ?>" type="text" placeholder="Data provider name"  required="" name="provider_name" class="form-control " />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="control-label" for="description">Description <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="">
                                            <textarea name="description" id="description" class="form-control" placeholder="Description..."><?php echo $this->set_field_value('description', ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xs-12 comp-grid">
                        <div class="card-body">
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="control-label" for="description">Main Data Uploader <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="">
                                            <?php
                                                echo SharedController::getSimpleInput("primary_data_uploader", InputType::hidden, (isset($this->page_props['primary_data_uploader_id']) ? $this->page_props['primary_data_uploader_id'] : $this->set_field_value('primary_data_uploader', '')));
                                                echo SharedController::getSimpleInput(
                                                    "user_search", 
                                                    InputType::text, 
                                                    $this->set_field_value('user_search', $this->set_field_value('primary_data_uploader', '')),
                                                    array(
                                                        "data-autocomplete" => get_link('shared/autocomplete'),
                                                        "data-autocomplete-value-control" => "primary_data_uploader",
                                                        "data-autocomplete-empty-message" => "No matching results",
                                                        "placeholder" => "Search users by name, surname, or username..."
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="control-label" for="description">Secondary Data Uploader</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="">
                                            <?php
                                                echo SharedController::getSimpleInput("secondary_data_uploader", InputType::hidden, $this->set_field_value('secondary_data_uploader', ''));
                                                echo SharedController::getSimpleInput(
                                                    "user_search_two", 
                                                    InputType::text, 
                                                    $this->set_field_value('user_search_two', $this->set_field_value('secondary_data_uploader', '')), 
                                                    array(
                                                        "data-autocomplete" => get_link('shared/autocomplete'),
                                                        "data-autocomplete-value-control" => "secondary_data_uploader",
                                                        "data-autocomplete-empty-message" => "No matching results",
                                                        "placeholder" => "Search users by name, surname, or username..."
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert lead">
                    Check all goals + indicators for which the provider can supply data:
                </div>
                <div class="row">
                    <?php
                    echo '<div class="col-lg-4 col-xs-12">';
                    echo '<ul>';

                    $count = 1;

                    foreach ($goals as $goal) {
                        $indicators = $db->rawQuery("SELECT * FROM `indicator` WHERE `goal_number` = {$goal['goal_number']} ORDER BY `goal_number` ASC");
                        
                        if (!empty(Router::$page_id)) {
                            $query = "SELECT 
                                        `indicator_data_provider`.indicator_id 
                                    FROM `indicator_data_provider` 
                                        INNER JOIN `indicator` ON `indicator`.id = `indicator_data_provider`.indicator_id
                                    WHERE `indicator_data_provider`.data_provider_id = " . Router::$page_id . "
                                    AND `indicator`.goal_number = {$goal['goal_number']}";
                            $provider_indicators = $db->rawQueryValue($query);
                        } else {
                            $provider_indicators = array();
                        }
                        
                        echo '<li>';
                        echo '<div class="form-check">
                                <input type="checkbox" class="form-check-input goal-check" id="goal_', $goal['goal_number'] , '" value="', $goal['goal_number'] , '" ' . (!empty($indicators) && !empty($provider_indicators) && count($indicators) == count($provider_indicators) ? 'checked' : '') . '>
                                <label class="form-check-label" for="goal_', $goal['goal_number'] , '">', $goal['goal_number'] , ' - ' , $goal['goal_name'] , '</label>
                              </div>';
                        
                        if (count($indicators) > 0) {
                            echo '<ul>';

                            foreach ($indicators as $indicator) {
                                echo '<li>';
                                echo '<div class="form-check">';
                                echo '<input type="checkbox" class="form-check-input goal-indicator-check" name="indicator_', $goal['goal_number'] , '_' , $indicator['id'] , '" id="indicator_', $goal['goal_number'] , '_' , $indicator['id'] , '" data-goal="', $goal['goal_number'] , '" ' . (!empty($provider_indicators) && in_array($indicator['id'], $provider_indicators) ? 'checked' : '') . '>';
                                echo '<label class="form-check-label" for="indicator_', $goal['goal_number'] , '_' , $indicator['id'] , '">', $indicator['indicator_name'] , '</label>';
                                echo '</div>';
                                echo '</li>';
                            }

                            echo '</ul>';
                            echo '</li>';
                        }

                        if ($count % 6 == 0) {
                            echo '</ul>';
                            echo '</div>';

                            if ($count != count($goals)) {
                                echo '<div class="col-lg-4 col-xs-12">';
                                echo '<ul>';
                            }
                        }

                        $count++;
                    }

                    echo '</ul>';
                    echo '</div>';
                    ?>
                </div>
                <div class="form-group form-submit-btn-holder text-center">
                    <button class="btn btn-primary" type="submit">
                        Save&nbsp;<i class="fa fa-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>
<script type="text/javascript">
    $(function() {
       $('.goal-check').click(function(){
           var selector = 'input[name^="indicator_' + this.value + '_"';
           
           if ($(this).is(':checked')) {
               $(selector).prop('checked', true);
           } else {
               $(selector).prop('checked', false);
           }
       }); 
       
       $('.goal-indicator-check').click(function() {
          var goal = $(this).data('goal'), all_checked = true;
          
          var selector = 'input[name^="indicator_' + goal + '_"';
          
          $(selector).each(function() {
             if (!$(this).is(':checked')) {
                 all_checked = false;
                 return false;
             } 
          });
          
          if (all_checked) {
              $('#goal_' + goal).prop('checked', true);
          } else {
              $('#goal_' + goal).prop('checked', false);
          }
       });
       
//       $('#data_uploader').on('change', function() {
//           if ($(this).val() == '99') {
//               $('#main-page-modal').modal('show');
//               $('#main-page-modal-body').load('<?= get_link('users/add?is_ajax=true') ?>');
//           }
//       });
    });
</script>