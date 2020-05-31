
<div class="container-fluid">
    <h1>Search Results</h1>
<?php
//Page Data From Controller
$view_data = $this->view_data;

function highligh_text($text, $string) {
    return str_replace($text, "<mark>$text</mark>", $string);
}

$term = $_GET['term'];

if (!empty($this->page_error)) {
    $this :: display_page_errors();
} else {
    ?>
    <h2 class="lead"><strong class="text-danger"><?= count($view_data) ?></strong> results were found for the search for <strong class="text-danger"><?= $term ?></strong></h2>
    <div class="mb-3"></div>
    <?php
    foreach ($view_data as $result) {
        $page = ($result['type'] == '1' ? 'home/goal/' : ($result['type'] == '2' ? 'home/indicator/' : ($result['type'] == '3' ? 'home/target/' : '')));
        $link = get_link($page . $result['id']);
        echo '<article>';
        echo '<h5><a href="', $link, '" title="">', highligh_text($term, $result['name']), '</a></h5>';
        
        if (!empty($result['summary'])) {
            echo '<p>', highligh_text($term, $result['summary']), '</p>';
        }
        
        echo '<p>', highligh_text($term, $result['description']), '</p>';
        echo '</article>';
    }
}

?>
</div>
