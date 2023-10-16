<?php

include_once plugin_dir_path(__FILE__) . 'utilities.php';

class LS_Shortcodes {

    static function video($attributes) {
        include_once plugin_dir_path(__FILE__) . 'video_database.php';
        $video_id = $attributes['id'] ?? null;
        $tag_slug = $attributes['tag'] ?? null;
        $thumbnail = in_array("thumbnail", array_values($attributes));
        $title = $attributes['title'] ?? "none";
        $play_icon = $attributes['play_icon'] ?? "none";
        $lightbox = $thumbnail && in_array("lightbox", array_values($attributes));
        $width = $attributes['width'] ?? null;
        $effect = $attributes['effect'] ?? null;
        $db = new VideoDatabase();
        $iframe_hidden = $thumbnail && !$lightbox;


        $video;
        if ($video_id) {
            $video = $db->get_video($video_id);
        } else if ($tag_slug) {
            $video = $db->newest_video_with_tag($tag_slug);
        }

        if ($video == null) {
            return "";
        }

        return get_video_div($video, $effect, $width, $thumbnail, $lightbox, $play_icon, $title, $iframe_hidden);
    }

    static function gallery($attributes) {
        include_once plugin_dir_path(__FILE__) . 'video_database.php';
        if ($attributes == "") $attributes = [];
        $tags = explode(",", $attributes['tags'] ?? "");
        $tag_picker = in_array("tag_picker", array_values($attributes));
        $thumbnail = in_array("thumbnail", array_values($attributes));
        $title = $attributes["title"] ?? null;
        $play_icon = $attributes["play_icon"] ?? null;
        $lightbox = in_array("lightbox", array_values($attributes));
        $width = $attributes['width'] ?? null;
        $effect = $attributes['effect'] ?? null;
        $iframe_hidden = $thumbnail && !$lightbox;

        return get_video_gallery($tags, $tag_picker, $thumbnail, $width, $title, $play_icon, $lightbox, $iframe_hidden, $effect);
    }
}


add_shortcode('ls-vid', array('LS_Shortcodes', 'video'));
add_shortcode('ls-gallery', array('LS_Shortcodes', 'gallery'));
