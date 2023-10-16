<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/utilities.php';
$video_database = new VideoDatabase();

$sort_by = $_GET['orderby'] ?? "name";
$sort_order = $_GET['order'] ?? "asc";
$per_page_options = [10, 25, 50];
$per_page = $_GET['per_page'] ?? $per_page_options[0];
$paged = $_GET['paged'] ?? 0;
$add_expanded = $_GET['add_expanded'] ?? false;
$tags = $video_database->get_tags();
$count = count($tags);
$pages = ceil($count / $per_page);

if ($paged > $pages) {
    $paged = $pages - 1;
} else if ($paged < 0) {
    $paged = 0;
}

switch ($sort_by) {
    case 'name':
        usort($tags, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });
        break;
    case 'slug':
        usort($tags, function ($a, $b) {
            return strcmp($a->slug, $b->slug);
        });
        break;
}

if ($sort_order === "desc") {
    $tags = array_reverse($tags);
}

if (count($tags) > $per_page) {
    $tags = array_slice($tags, $per_page * $paged, $per_page);
}


?>
<div class="wrap">
    <h1 class="wp-heading-inline">Tags</h1>
    <a href="<? echo get_redirect(new_params: ['add_expanded' => !$add_expanded]) ?>" class="page-title-action aria-button-if-js" role="button" aria-expanded="true"><? echo $add_expanded ? "Cancel Add" : "Add New"; ?></a>
    <div class="card" <? echo $add_expanded ? "" : 'hidden'; ?>>
        <form action="/wp-json/ls-vid-gallery/v1/tags" method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="tag_name">Name</label></th>
                        <td><input name="tag_name" type="text" id="tag_name" class="regular-text"></td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" id="redirect" name="redirect" value="<?php echo get_redirect(new_params: ['add_expanded' => false]); ?>">
            <input type="hidden" id="action" name="action" value="add">
            <input class="button-primary" type="submit" name="add_button" value="Add Tag" />
        </form>
    </div>

    <br>

    <div style="margin-top: 25px; padding-bottom: 25px;">
        <div style="float: left;">
            <form>
                <label for="per_page_picker">Per Page</label>
                <select id="per_page_picker" name="per_page_picker" onchange="window.location.href = document.getElementById('per_page_picker').value;">
                    <? foreach ($per_page_options as $option) { ?>
                        <option value="<?php echo get_redirect(new_params: ["per_page" => $option, "paged" => 0]); ?>" <?php echo $option == $per_page ? "selected" : ""; ?>><?php echo $option; ?></option>
                    <?php } ?>
                </select>
            </form>
        </div>
        <div class="tablenav-pages" style="float: right;"><span class="displaying-num"><?php echo $count; ?> <?php echo ($count) ? "items" : "item"; ?></span>
            <span class="pagination-links">
                <a class="first-page button" href="<? echo get_redirect(new_params: ['paged' => 0]); ?>" <? if ($paged == 0) echo "style='display: none;'" ?>>
                    <span class="screen-reader-text">First page</span>
                    <span aria-hidden="true">«</span>
                </a>
                <a class="prev-page button" href="<? echo get_redirect(new_params: ["paged" => $paged - 1]) ?>" <? if ($paged == 0) echo "style='display: none;'" ?>>
                    <span class="screen-reader-text">Previous page</span>
                    <span aria-hidden="true">‹</span>
                </a>
                <span class="paging-input">
                    <label for="current-page-selector" class="screen-reader-text">Current Page</label>
                    <input class="current-page" id="current-page-selector" type="text" name="paged" value="<? echo $paged + 1 ?>" size="1" aria-describedby="table-paging" data-url="<? echo get_redirect(new_params: ["paged" => "NEWPAGE"]) ?>" onchange="
                    ( function() {
                        $input = document.getElementById('current-page-selector');
                        $url = $input.dataset.url;
                        $url = $url.replace('NEWPAGE', $input.value - 1);
                        window.location.href = $url;
                    })();
                    ">
                    <span class="tablenav-paging-text"> of <span class="total-pages"><? echo $pages ?></span>
                    </span>
                </span>
                <a class="next-page button" href="<? echo get_redirect(new_params: ["paged" => $paged + 1]) ?>" <? if ($paged + 1 >= $pages) echo "style='display: none;'" ?>>
                    <span class="screen-reader-text">Next page</span>
                    <span aria-hidden="true">›</span>
                </a>
                <a class="last-page button" href="<? echo get_redirect(new_params: ["paged" => $pages - 1]) ?>" <? if ($paged + 1 >= $pages) echo "style='display: none;'" ?>>
                    <span class="screen-reader-text">Last page</span>
                    <span aria-hidden="true">»</span>
                </a>
            </span>
        </div>
    </div>

    <br>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th class="row-title <? echo ($sort_by == "name") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "name", "order" => ($sort_by != "name") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('Name', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>
                </th>
                <th class="row-title <? echo ($sort_by == "slug") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "slug", "order" => ($sort_by != "slug") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('Slug', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>
                </th>
                <th><?php esc_attr_e('Video Count', 'WpAdminStyle'); ?></th>
                <th><?php esc_attr_e('Delete', 'WpAdminStyle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($tags as $tag) { ?>
                <tr>
                    <td class="row-title"><?php echo $tag->name; ?></td>
                    <td class="row-title"><?php echo $tag->slug; ?></td>
                    <td><?php echo $tag->video_count; ?></td>
                    <td>
                        <form action="/wp-json/ls-vid-gallery/v1/tags" method="post">
                            <input type="hidden" id="action" name="action" value="delete">
                            <input type="hidden" id="vid_id" name="tag_slug" value="<?php echo $tag->slug; ?>">
                            <input type="hidden" id="redirect" name="redirect" value="<?php echo get_redirect(new_params: ['add_expanded' => false]); ?>">
                            <input style="color: red; border: none; background: none; padding: 0; margin: 0; cursor: pointer;" class="button-small" type="submit" name="add_button" value="Delete" />
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="row-title <? echo ($sort_by == "name") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "name", "order" => ($sort_by != "name") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('Name', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>
                </th>
                <th class="row-title <? echo ($sort_by == "slug") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "slug", "order" => ($sort_by != "slug") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('Slug', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>
                </th>
                <th><?php esc_attr_e('Video Count', 'WpAdminStyle'); ?></th>
                <th><?php esc_attr_e('Delete', 'WpAdminStyle'); ?></th>
            </tr>
        </tfoot>
    </table>

</div>