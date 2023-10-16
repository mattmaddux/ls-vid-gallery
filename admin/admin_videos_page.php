<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/utilities.php';
$video_database = new VideoDatabase();

$sort_by = $_GET['orderby'] ?? "id";
$sort_order = $_GET['order'] ?? "asc";
$per_page_options = [10, 25, 50];
$per_page = $_GET['per_page'] ?? $per_page_options[0];
$paged = $_GET['paged'] ?? 0;
$add_expanded = $_GET['add_expanded'] ?? false;
$vids = $video_database->get_videos();
$count = count($vids);
$pages = ceil($count / $per_page);
$tags = $video_database->get_tags();

if ($paged > $pages) {
    $paged = $pages - 1;
} else if ($paged < 0) {
    $paged = 0;
}

switch ($sort_by) {
    case 'id':
        usort($vids, function ($a, $b) {
            return $a->id - $b->id;
        });
        break;
    case 'name':
        usort($vids, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });
        break;
    case 'add_date':
        usort($vids, function ($a, $b) {
            return comp_date($a->add_date, $b->add_date);
        });
        break;
}

if ($sort_order === "desc") {
    $vids = array_reverse($vids);
}

if (count($vids) > $per_page) {
    $vids = array_slice($vids, $per_page * $paged, $per_page);
}


?>
<div class="wrap">
    <h1 class="wp-heading-inline">Videos</h1>
    <a href="<? echo get_redirect(new_params: ['add_expanded' => !$add_expanded]) ?>" class="page-title-action aria-button-if-js" role="button" aria-expanded="true"><? echo $add_expanded ? "Cancel Add" : "Add New"; ?></a>
    <div class="card" <? echo $add_expanded ? "" : 'hidden'; ?>>
        <form action="/wp-json/ls-vid-gallery/v1/videos" method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="vid_name">Display Name</label></th>
                        <td><input name="vid_name" type="text" id="vid_name" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vid_type">Site</label></th>
                        <td>
                            <select name="vid_type" id="vid_type">
                                <?php foreach (VideoType::cases() as $vid_type) { ?>
                                    <option value="<?php echo $vid_type->name; ?>"><?php echo $vid_type->name; ?></option>
                                <?php } ?>
                                <!-- <option selected="selected" value="option_one">Option #1</option>
                            <option value="option_2">Option #2</option> -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vid_id">Site ID</label></th>
                        <td><input name="vid_id" type="text" id="vid_id" class="regular-text"></td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" id="redirect" name="redirect" value="<?php echo get_redirect(new_params: ['add_expanded' => false]); ?>">
            <input type="hidden" id="action" name="action" value="add">
            <input class="button-primary" type="submit" name="add_button" value="Add Video" />
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
                <th class="<? echo ($sort_by == "id") ? "sorted $sort_order" : ""; ?>" style="width: 60px;">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "id", "order" => ($sort_by != "id") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('ID', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>

                </th>
                <th style="width: 200px;" class="row-title <? echo ($sort_by == "name") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "name", "order" => ($sort_by != "name") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('Display Name', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>
                </th>
                <th style="width: 80px;"><?php esc_attr_e('Site', 'WpAdminStyle'); ?></th>
                <th style="width: 100px;"><?php esc_attr_e('Site ID', 'WpAdminStyle'); ?></th>
                <th style="width: 120px;" class="row-title <? echo ($sort_by == "add_date") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "add_date", "order" => ($sort_by != "add_date") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('Date Added', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>
                </th>
                <th><?php esc_attr_e('Tags', 'WpAdminStyle'); ?></th>
                <th style="width: 60px;"><?php esc_attr_e('View', 'WpAdminStyle'); ?></th>
                <th style="width: 65px;"><?php esc_attr_e('Delete', 'WpAdminStyle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($vids as $vid) {
                $slugs = array_map(function ($tag) {
                    return $tag->slug;
                }, $vid->tags);
            ?>
                <tr>
                    <td class="row-title"><?php echo $vid->id; ?></td>
                    <td class="row-title"><?php echo $vid->name; ?></td>
                    <td><?php echo $vid->type->name; ?></td>
                    <td><?php echo $vid->site_id; ?></td>
                    <td><?php echo date_to_string($vid->add_date); ?></td>
                    <td>
                        <div style="display: flex; flex-wrap: wrap;">
                            <?php
                            foreach ($vid->tags as $tag) { ?>
                                <div class="dropdown">
                                    <button class="btn" style="background-color: none; padding: 0px;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="badge text-bg-primary" style=""><?php echo $tag->name; ?></span>
                                    </button>
                                    <ul class="dropdown-menu" style="max-height: 45px;">
                                        <form action=" /wp-json/ls-vid-gallery/v1/videos" method="post">
                                            <input type="hidden" id="action" name="action" value="unlink_tag">
                                            <input type="hidden" id="vid_id" name="vid_id" value="<?php echo $vid->id; ?>">
                                            <input type="hidden" id="tag_slug" name="tag_slug" value="<?php echo $tag->slug; ?>">
                                            <input type="hidden" id="redirect" name="redirect" value="<?php echo get_redirect(new_params: ['add_expanded' => false]); ?>">
                                            <li><a class="dropdown-item" href="#" onclick="this.closest('form').submit(); return false;">Remove Tag</a></li>
                                        </form>
                                    </ul>
                                </div>
                            <? } ?>
                            <div class="dropdown">
                                <button class="btn" style="width: 30px; height: 20px; background-color: none; padding: 0px;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="dashicons dashicons-plus-alt" style="color: #0d6efd;"></span>
                                </button>
                                <ul class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                                    <? foreach ($tags as $tag) {
                                        if (in_array($tag->slug, $slugs)) continue;
                                    ?>
                                        <form action="/wp-json/ls-vid-gallery/v1/videos" method="post">
                                            <input type="hidden" id="action" name="action" value="link_tag">
                                            <input type="hidden" id="vid_id" name="vid_id" value="<?php echo $vid->id; ?>">
                                            <input type="hidden" id="tag_slug" name="tag_slug" value="<?php echo $tag->slug; ?>">
                                            <input type="hidden" id="redirect" name="redirect" value="<?php echo get_redirect(new_params: ['add_expanded' => false]); ?>">
                                            <li><a class="dropdown-item" href="#" onclick="this.closest('form').submit(); return false;"><?php echo $tag->name; ?></a></li>
                                        </form>
                                    <? } ?>
                                </ul>
                            </div>
                        </div>
                    </td>
                    <td><a href="https://youtube.com">View</a></td>
                    <td>
                        <form action="/wp-json/ls-vid-gallery/v1/videos" method="post">
                            <input type="hidden" id="action" name="action" value="delete">
                            <input type="hidden" id="vid_id" name="vid_id" value="<?php echo $vid->id; ?>">
                            <input type="hidden" id="redirect" name="redirect" value="<?php echo get_redirect(new_params: ['add_expanded' => false]); ?>">
                            <input style="color: red; border: none; background: none; padding: 0; margin: 0; cursor: pointer;" class="button-small" type="submit" name="add_button" value="Delete" />
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="row-title <? echo ($sort_by == "id") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "id", "order" => ($sort_by != "id") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('ID', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">

                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>

                </th>
                <th class="row-title <? echo ($sort_by == "name") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "name", "order" => ($sort_by != "name") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('Display Name', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>
                </th>
                <th><?php esc_attr_e('Site', 'WpAdminStyle'); ?></th>
                <th><?php esc_attr_e('Site ID', 'WpAdminStyle'); ?></th>
                <th class="row-title <? echo ($sort_by == "add_date") ? "sorted $sort_order" : ""; ?>">
                    <a style="display: flex;" href="<? echo get_redirect(new_params: ["orderby" => "add_date", "order" => ($sort_by != "add_date") ? "asc" : (($sort_order == "asc") ? "desc" : "asc")]) ?>"><?php esc_attr_e('Date Added', 'WpAdminStyle'); ?>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc" aria-hidden="true" ?></span>
                            <span class="sorting-indicator desc" aria-hidden="true"></span>
                        </span>
                    </a>
                </th>
                <th><?php esc_attr_e('Tags', 'WpAdminStyle'); ?></th>
                <th><?php esc_attr_e('View', 'WpAdminStyle'); ?></th>
                <th><?php esc_attr_e('Delete', 'WpAdminStyle'); ?></th>
            </tr>
        </tfoot>
    </table>

</div>