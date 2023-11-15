<?php

class VideoAPI {


    static function process_video_request(WP_REST_Request $request) {

        $action = $request->get_param('action');
        switch ($action) {
            case 'add':
                self::add_video($request);
                break;
            case 'delete':
                self::delete_video($request);
                break;
            case 'link_tag':
                self::link_tag($request);
                break;
            case 'unlink_tag':
                self::unlink_tag($request);
                break;
            default:
                throw new Exception("Invalid action");
        }
    }

    static function add_video(WP_REST_Request $request) {
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
        $name = $request->get_param('vid_name');
        $type = $request->get_param('vid_type');
        $site_id = $request->get_param('vid_id');
        $redirect = $request->get_param('redirect');

        $video_db = new VideoDatabase();
        $video_db->add_video($name, $type, $site_id);

        wp_redirect($redirect);
        exit();
    }

    static function delete_video(WP_REST_Request $request) {
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
        $id = $request->get_param('vid_id');
        $redirect = $request->get_param('redirect');

        $video_db = new VideoDatabase();
        $video_db->remove_video($id);

        wp_redirect($redirect);
        exit();
    }

    static function link_tag(WP_REST_Request $request) {
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
        $video = $request->get_param('vid_id');
        $tag = $request->get_param('tag_slug');
        $redirect = $request->get_param('redirect');

        $video_db = new VideoDatabase();
        $video_db->link_video_tag($video, $tag);

        wp_redirect($redirect);
        exit();
    }

    static function unlink_tag(WP_REST_Request $request) {
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
        $video = $request->get_param('vid_id');
        $tag = $request->get_param('tag_slug');
        $redirect = $request->get_param('redirect');

        $video_db = new VideoDatabase();
        $video_db->unlink_video_tag($video, $tag);

        wp_redirect($redirect);
        exit();
    }

    static function process_tag_request(WP_REST_Request $request) {
        $action = $request->get_param('action');
        switch ($action) {
            case 'add':
                self::add_tag($request);
                break;
            case 'delete':
                self::delete_tag($request);
                break;
            default:
                throw new Exception("Invalid action");
        }
    }

    static function add_tag(WP_REST_Request $request) {
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
        $name = $request->get_param('tag_name');
        $redirect = $request->get_param('redirect');

        $video_db = new VideoDatabase();
        $video_db->add_tag($name);

        wp_redirect($redirect);
        exit();
    }


    static function delete_tag(WP_REST_Request $request) {
        include_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
        $slug = $request->get_param('tag_slug');
        $redirect = $request->get_param('redirect');

        $video_db = new VideoDatabase();
        $video_db->remove_tag($slug);

        wp_redirect($redirect);
        exit();
    }

    static function register_endpoints() {
        register_rest_route('ls-vid-gallery/v1', '/videos', array(
            'methods' => 'POST',
            'callback' => array('VideoAPI', 'process_video_request'),
        ));
        register_rest_route('ls-vid-gallery/v1', '/tags', array(
            'methods' => 'POST',
            'callback' => array('VideoAPI', 'process_tag_request'),
        ));
    }
}

// URL: /wp-json/ls-vid-gallery/v1/test