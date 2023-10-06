<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
$video_database = new VideoDatabase();

// $video_database->add_video('Ski Retreat', VideoType::YouTube, "LdNLOYbK-zA");
// $video_database->add_tag('ski_retreat', "Ski Retreat");
// $video_database->link_video_tag(2, "ski_retreat");


// $vids = $video_database->get_videos();
// $tags = $video_database->get_tags();

// $video_database->unlink_video_tag(1, "ski_retreat");

// $video_database->remove_video(1);
$video_database->remove_tag("ski_retreat");


?>
<div>
    <h1>LS Video Gallery</h1>
    <p>This is the admin page for the plugin.</p>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="input_id">Video Name</label></th>
                <td><input name="input_id" type="text" id="vid_name" value="" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="input_id">Video URL</label></th>
                <td><input name="input_id" type="text" id="vid_url" value="Lorem Ipsum is that!" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row">Checkbox</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <span>checkbox</span>
                        </legend>
                        <label for="checkbox_id">
                            <input name="checkbox_id" type="checkbox" id="checkbox_id" value="1">
                            Just check me!
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="select_id">Select</label></th>
                <td>
                    <select name="select_id" id="select_id">
                        <option selected="selected" value="option_one">Option #1</option>
                        <option value="option_2">Option #2</option>
                        <option value="option_3">Option #3</option>
                        <option value="option_4">Option #4</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">Paragraph</th>
                <td>
                    <p>
                        It is a long established fact that a reader will be distracted by the readable
                        content of a page when looking at its layout. The point of using Lorem Ipsum is
                        that it has a more-or-less normal distribution of letters, as opposed to using
                        'Content here, content here', making it look like readable English.
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">Description</th>
                <td>
                    <p class="description">
                        It is a long established fact that a reader will be distracted by the readable
                        content of a page when looking at its layout. The point of using Lorem Ipsum is
                        that it has a more-or-less normal distribution of letters, as opposed to using
                        'Content here, content here', making it look like readable English.
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

</div>