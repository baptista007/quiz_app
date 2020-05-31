<?php
$view_data = $this->view_data;
$goal = $view_data->goalrecords;
$goal_list = $view_data->goal_list;
$target = $view_data->targetrecords;
$indicators = $view_data->indicatorrecords;
$db = PDODb::getInstance();

$current_index = 0;
//search array for specific object index
foreach($goal_list as $key => $value) {
    if ($value["goal_number"] === $goal["goal_number"]) {
        $current_index = $key;
        break;
    }
}
//$current_index = array_search($goal['goal_id'], $goal_list, true);
$next_page_goal_id = 0;
$previous_page_goal_id = 0;

// check current index and assign next goal id
if($current_index >= 0 && $current_index < count($goal_list) - 1){
    $next_page_goal_id = $goal_list[$current_index +1]["goal_number"];
}
else{
    $next_page_goal_id = 0;
}

if($current_index > 0 && $previous_page_goal_id < count($goal_list) - 1){
    $previous_page_goal_id = $goal_list[$current_index - 1]["goal_number"];
}
else{
    $previous_page_goal_id = 0;
}

//var_dump(json_encode(count($goal_list), JSON_NUMERIC_CHECK) );
//echo '<br/> <br/>';
//var_dump(json_encode($current_index, JSON_NUMERIC_CHECK));
?>
<div class="container-fluid">
    <div class="row" style="background-color: #<?= $goal['color'] ?>; color: #ffffff;">
        <div class="col-lg-12 p-3">
            <div class="row">
                <div class="col-lg-10">
                    <h1 id="goal-title" class="custom-title">
                        <img src="<?= SDG_SVG_DIR . $goal["icon"] ?>" class="sdg-img">
                        <?= $goal['goal_name'] ?>
                    </h1>
                </div>
                <div class="col-lg-2">
                    <a id="goal-nav-prev" class="<?php echo ($previous_page_goal_id === 0 ? 'isDisabled' : '') ?>" href="<?php echo get_link('home/goal/' . $previous_page_goal_id ); ?>"><i class="fa fa-arrow-circle-left fa-3x pagination-icon-color"></i></a>
                    <a id="goal-nav-next" class="<?php echo ($next_page_goal_id === 0 ? 'isDisabled' : '') ?>" href="<?php echo get_link('home/goal/' . $next_page_goal_id ); ?>"><i class="fa fa-arrow-circle-right fa-3x pagination-icon-color"></i></a>
                </div>
            </div>
            <div class="row p-3">
                <div id="goal-summary" class="lead">
                    <?= $goal['summary'] ?>
                </div>
            </div>            
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <ul id="cd-breadcrumb" class="cd-breadcrumb">
                <li><a href="<?php echo SITE_ADDR ; ?>">Home</a></li>
                <li>Goal - <?= $goal['goal_name'] ?></li>
             </ul>
        </div>
        <div id="tabPanel" style="display: block;" class="goalSummary">
            <div display="flex" class="ldAByT">
                <?php 
                    $goal_indicator_data_count = $db->rawQuery("SELECT DISTINCT indicator_id AS Total FROM indicator_data WHERE indicator_ref_id IN (SELECT indicator_id FROM indicator WHERE goal_number = {$goal["goal_number"]});");
                    $total_targets = count($db->rawQuery("SELECT * FROM target WHERE goal_number = {$goal["goal_number"]}"));
                    $all_goal_indicators = $db->rawQuery("SELECT * FROM indicator WHERE goal_number = {$goal["goal_number"]}");
                    $percent;
                    
                    if(count($all_goal_indicators) > 0){
                        $percent = (count($goal_indicator_data_count) / count($all_goal_indicators)) * 100;
                    }
                    else {
                        $percent = 0;
                    }
                ?>
                <div width="1,,0.3333333333333333" class="giitem">
                    <h1 class="gisHeader"><?= $total_targets ?></h1>
                    <p class="gistext">Total targets for Goal <?= $goal['goal_number'] ?> in Namibia</p>
                </div>
                <div width="1,,0.3333333333333333" class="giitem">
                    <h1 class="gisHeader"><?= count($goal_indicator_data_count);?>/<?= count($all_goal_indicators);?></h1>
                    <p class="gistext">indicators with data available for Goal <?= $goal['goal_number'] ?> in Namibia</p>
                </div>
                <div width="1,,0.3333333333333333" class="giitem">
                    <h1 class="gisHeader"><?= round($percent, 1) ?>%</h1>
                    <p class="gistext">indicator coverage for Goal <?= $goal['goal_number'] ?> in Namibia</p>
                </div>
            </div>
        </div>
        <div class="ckbsZU">
            <hr class="jrVoHA">
        </div>
        <div class="p-3">
            <h1 class="custom-title">Targets</h1>
        </div>
        <?php
        $count = 1;
        
        
        echo '<div class="row mb-3">';

        foreach ($target as $targetrow) {
            echo '<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">';
//                echo '<div class="content">';
                    echo '<div class="cards">';
                        echo '<div id="goal-targets" class="card-custom">';
                            echo '<a class="zoom" href="' . get_link('home/target/' . $targetrow['id']) . '">';
                                echo '<img src="' . SDG_SVG_DIR_TARGET . $targetrow['icon'] . '" alt="">';
                            echo '</a>';                            
                        echo '</div>'; //card                                                
                    echo '</div>';
//                echo '</div>'; //content
            echo '</div>'; //col-lg-2

            if ($count % 12 == 0) {
                echo '</div>';

                if ($count != count($target)) {
                    echo '<div class="row mb-3">';
                }
            }

            $count++;
        }
        ?>
    </div>
</div>

