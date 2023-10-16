<?php

function get_redirect(array $new_params = []) {
    $params = $_GET;
    $params = array_merge($_GET, $new_params);
    $current_url = explode("?", $_SERVER['REQUEST_URI']);
    $params_string = '?';
    foreach ($params as $key => $value) {
        if ($params_string !== '?')
            $params_string .= '&';
        $params_string .= "$key=$value";
    }
    return $current_url[0] . $params_string;
}


function date_to_string($date) {
    return "$date[mon]-$date[mday]-$date[year]";
}

function comp_date(array $a, array $b) {
    if ($a["year"] < $b["year"]) {
        return -1;
    } else if ($a["year"] > $b["year"]) {
        return 1;
    } else if ($a["mon"] < $b["mon"]) {
        return -1;
    } else if ($a["mon"] > $b["mon"]) {
        return 1;
    } else if ($a["mday"] < $b["mday"]) {
        return -1;
    } else if ($a["mday"] > $b["mday"]) {
        return 1;
    } else {
        return 0;
    }
}



function get_vid_thumb($type, $id) {
    $thumb_url = "";
    switch ($type) {
        case VideoType::YouTube:
            $thumb_url = "https://img.youtube.com/vi/$id/maxresdefault.jpg";
            break;
        case VideoType::Vimeo:
            $data = json_decode(file_get_contents("https://vimeo.com/api/v2/video/$id.json"));
            $thumb_url = $data[0]->thumbnail_large;
            break;
    }
    return '<img class="video_thumb" id="thumb_' . $id . '" width="100%" height="100%" src=' . $thumb_url . ' alt="Video thumbnail" />';
}




function get_vid_embed($type, $id, $hidden = false) {
    $class_code = "class='video_embed" . (($hidden) ? ' hidden' : '') . "' ";
    $id_code = "id='embed_$id' ";
    $flags_code = "frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen";

    $src_code = "";
    switch ($type) {
        case VideoType::YouTube:
            $src_code = "src='//www.youtube.com/embed/$id' ";
            break;
        case VideoType::Vimeo:
            $src_code = "src='https://player.vimeo.com/video/$id' ";
            break;
    }

    return '<iframe ' . $class_code . $id_code . $src_code . $flags_code . '></iframe>';
}

function get_lightbox($type, $video_id) {
    $embed_code = get_vid_embed($type, $video_id);
    $id = "id='lightbox_$video_id' ";
    // $on_click = "onClick='toggleVideo(\"$video_id\");' ";
    return "<div $id class='lightbox hidden'>
                <div class='lightbox-content'>
                    $embed_code
                </div>
            </div>";
}


function get_video_div($video, $effect, $width, $thumbnail, $lightbox, $play_icon, $title, $iframe_hidden) {
    $id = "id='wrapper_$video->site_id' ";
    $class = "class='video_wrapper $effect ";
    $tag_slugs = array_map(function ($tag) {
        return $tag->slug;
    }, $video->tags);
    $data_vid = "data-vid='$video->site_id' ";
    $data_tags = "data-tags='" . implode(",", $tag_slugs) . "' ";
    $class .= "' ";
    $on_click = "onClick='toggleVideo(\"$video->site_id\");' ";
    $on_load = "onload='sizeOverlays(\"$video->site_id\");' ";
    $size_style = ($width) ? "style='width: $width' " : "style='width: 100%'";
    $thumb_code = ($thumbnail) ? get_vid_thumb($video->type, $video->site_id) : "";
    $embed_code = (!$lightbox) ? get_vid_embed($video->type, $video->site_id, $iframe_hidden) : "";
    $lightbox_code = ($lightbox) ? get_lightbox($video->type, $video->site_id) : "";
    $overlay = get_overlay($play_icon, $title, $video);
    $hover_overlay = get_hover_overlay($play_icon, $title, $video);

    $main_div = "<div " . $id . $data_vid . $data_tags . $class . $size_style . $on_click . $on_load . ">" . $thumb_code . $embed_code . $lightbox_code . $overlay . $hover_overlay . "</div>";
    return $main_div;
}


function get_video_gallery($tags, $tag_picker, $thumbnail, $width, $title, $play_icon, $lightbox, $iframe_hidden, $effect) {
    $db = new VideoDatabase();
    $videos = $db->get_videos($tags);
    $width = $width ?? "300px";
    $gallery = "<div class='video_gallery'>";
    $tag_picker_code = ($tag_picker) ? get_tag_picker($tags) : "";
    foreach ($videos as $video) {
        $gallery .= get_video_div($video, $effect, $width, $thumbnail, $lightbox, $play_icon, $title, $iframe_hidden);
    }
    $gallery .= "</div>";
    return $tag_picker_code . $gallery;
}

function get_tag_picker($slugs) {
    $db = new VideoDatabase();
    $tags = $db->get_tags($slugs);
    $buttons = "";
    foreach ($tags as $tag) {
        $id = "id='button_$tag->slug'";
        $onClick = "onclick='toggleTag(\"$tag->slug\");'";
        $buttons .= "<button $id type='button' class='btn btn-secondary tag_button button_$tag->slug' $onClick>$tag->name</button>";
    }
    return "<div class='tag_picker_wrapper'>
                <div class='btn-group me-2' role='group' aria-label='Second group'>
                    $buttons
                </div>
            </div>";
}

function get_overlay($play_button, $show_title, $video) {
    if ($play_button != "always" && $show_title != "always") {
        return "";
    }
    $id = "id='overlay_$video->site_id' ";
    $always = ($play_button == "always" || $show_title == "always");
    $classes = "class='overlay always' ";
    $play_icon_code = ($play_button == "always") ? get_play_icon() : "";
    $title_code = ($show_title == "always") ? get_title($show_title, $video) : "";
    return "<div $id $classes>" . $play_icon_code . $title_code . "</div>";
}

function get_hover_overlay($play_icon, $show_title, $video) {
    if ($play_icon != "hover" && $show_title != "hover") {
        return "";
    }
    $id = "id='hover_overlay_$video->site_id' ";
    $classes = "class='overlay " . (($play_icon == "always" || $show_title == "always") ? "transparent" : "tinted") . "'";
    $play_icon_code = ($play_icon == "hover") ? get_play_icon() : "";
    $title_code = ($show_title == "hover") ? get_title($show_title, $video) : "";
    return "<div $id $classes>" . $play_icon_code . $title_code . "</div>";
}

function get_play_icon() {
    return "<div class='play_icon'><span class='dashicons dashicons-controls-play play_icon_span'></span></div>";
}

function get_title($title, $video) {
    return "<div class='video_title'>$video->name</div>";
}
