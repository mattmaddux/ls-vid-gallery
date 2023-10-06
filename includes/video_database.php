<?php

/**
 * Enum representing the type of video source.
 */
enum VideoType {
    case YouTube;
    case Vimeo;

    public static function fromName(string $name): static {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return $YouTube;
    }
}

/**
 * 
 * This class represents a video database and provides methods to interact with it.
 * 
 * @package LS_Vid_Gallery
 */
class VideoDatabase {
    private $wpdb;
    private $video_table;
    private $video_tag_table;
    private $video_tag_link_table;


    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        update_option('video_table', 'ls_video_vids');
        update_option('video_tag_table', 'ls_video_tags');
        update_option('video_tag_link_table', 'ls_video_tag_links');
        $this->video_table = $wpdb->prefix . get_option('video_table');
        $this->video_tag_table = $wpdb->prefix . get_option('video_tag_table');
        $this->video_tag_link_table = $wpdb->prefix . get_option('video_tag_link_table');
    }

    /*
        Private Methods
    */

    /**
     * Creates or updates the video table in the database.
     *
     * @return void
     */
    function update_video_table() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $table_name = $this->video_table;

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            type varchar(255) NOT NULL,
            site_id varchar(255) NOT NULL,
            add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Creates or updates the table in the database for video tags.
     *
     * @return void
     */
    function update_video_tag_table() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $table_name = $this->video_tag_table;

        $sql = "CREATE TABLE $table_name (
            slug varchar(255) NOT NULL UNIQUE,
            name varchar(255) NOT NULL,
            PRIMARY KEY  (slug)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Creates or updates the table in the database to link videos with tags.
     *
     * @return void
     */
    function update_video_tag_link_table() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $table_name = $this->video_tag_link_table;

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            video mediumint(9) NOT NULL,
            tag varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


    /*
        Public Methods
    */

    /**
     * Creates or updates the database tables needed for the plugin.
     *
     * @return void
     */
    public function update_db_tables() {
        $this->update_video_table();
        $this->update_video_tag_table();
        $this->update_video_tag_link_table();
    }

    /**
     * Drops all the tables created by the plugin.
     *
     * @return void
     */
    public function drop_db_tables() {
        $this->wpdb->query("DROP TABLE IF EXISTS {$this->video_table}");
        $this->wpdb->query("DROP TABLE IF EXISTS {$this->video_tag_table}");
        $this->wpdb->query("DROP TABLE IF EXISTS {$this->video_tag_link_table}");
    }

    /**
     * Adds a new video to the database.
     *
     * @param string $name The name of the video.
     * @param string $url The URL of the video.
     * @return void
     */
    public function add_video(string $name, VideoType $type, string $site_id) {
        $data = array(
            'name' => $name,
            'type' => $type->name,
            'site_id' => $site_id
        );
        $format = array('%s', '%s', '%s');
        $this->wpdb->insert($this->video_table, $data, $format);
    }

    public function remove_video($id) {
        $this->wpdb->delete($this->video_table, array('id' => $id), array('%d'));
        $this->wpdb->delete($this->video_tag_link_table, array('video' => $id), array('%d'));
    }

    public function add_tag($slug, $name) {
        $data = array(
            'slug' => $slug,
            'name' => $name
        );
        $format = array('%s', '%s');
        $this->wpdb->insert($this->video_tag_table, $data, $format);
    }

    public function remove_tag($slug) {
        $this->wpdb->delete($this->video_tag_table, array('slug' => $slug), array('%s'));
        $this->wpdb->delete($this->video_tag_link_table, array('tag' => $slug), array('%s'));
    }

    public function link_video_tag($video, $tag) {
        $data = array(
            'video' => $video,
            'tag' => $tag
        );
        $format = array('%d', '%s');
        $this->wpdb->insert($this->video_tag_link_table, $data, $format);
    }

    public function unlink_video_tag($video, $tag) {
        $this->wpdb->delete($this->video_tag_link_table, array('video' => $video, 'tag' => $tag), array('%d', '%s'));
    }

    public function get_videos(?string $tag = null) {
        if ($tag) {
            $videos = $this->wpdb->get_results("SELECT * FROM $this->video_table INNER JOIN $this->video_tag_link_table ON $this->video_table.id = $this->video_tag_link_table.video WHERE $this->video_tag_link_table.tag = $tag");
        } else {
            $videos = $this->wpdb->get_results("SELECT * FROM $this->video_table");
        }

        foreach ($videos as $video) {
            $video->type = VideoType::fromName($video->type);
            $tags = $this->wpdb->get_results("SELECT $this->video_tag_table.slug FROM $this->video_tag_table INNER JOIN $this->video_tag_link_table ON $this->video_tag_table.slug = $this->video_tag_link_table.tag WHERE $this->video_tag_link_table.video = $video->id");
            $video->tags = array_map(function ($tag) {
                return $tag->slug;
            }, $tags);
        }
        return $videos;
    }

    public function get_tags() {
        $tags = $this->wpdb->get_results("SELECT * FROM $this->video_tag_table");
        return $tags;
    }
}
