<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/video_database.php';
$video_database = new VideoDatabase();

?>
<div class="wrap">
    <h1 class="wp-heading-inline">Shortcodes</h1>
    <div class="container">
        <div class="row align-items-start">
            <div class="col">
                <div class="card">
                    <h4 class="wp-heading-inline">LS Vid</h4>
                    <pre><code>[ls-vid]</code></pre>
                    <p>Use this shortcode to embed a single video on a page. Use the options below to customize the video.</p>

                    <h5 class="wp-heading-inline">ID</h5>
                    <pre><code>[ls-vid id="id_here"]</code></pre>
                    <p>Use the <code>id</code> option to select a specific video.</p>

                    <h5 class="wp-heading-inline">Tag</h5>
                    <pre><code>[ls-vid tag="tag_slug_here"]</code></pre>
                    <p>Use the <code>tag</code> option to automatically choose the most recent video with the specified tag.</p>
                    <p>(Ignored if the <code>id</code> option is set.)</p>

                    <h5 class="wp-heading-inline">Thumbnail</h5>
                    <pre><code>[ls-vid thumbnail]</code></pre>
                    <p>Include the <code>thumbnail</code> option to display the thumbnail from the video site, rather than the embedded video frame. Clicking on the thumbnail will then load the video frame, which will automatically play.</p>

                    <h5 class="wp-heading-inline">Title</h5>
                    <pre><code>[ls-vid title="hover"]</code></pre>
                    <p>Set the <code>title</code> option to display the video's title on top of the thumbnail.</p>
                    <p>(Ignored unless <code>thumbnail</code> is set.)</p>
                    <p>Options are:</p>
                    <ul>
                        <li>
                            <p>
                                <code>none</code> (Default)
                            </p>
                        </li>
                        <li>
                            <p>
                                <code>always</code>
                            <p>
                        </li>
                        <li>
                            <p>
                                <code>hover</code>(When cursor is over the video.)
                            </p>
                        </li>
                    </ul>

                    <h5 class="wp-heading-inline">Play Icon</h5>
                    <pre><code>[ls-vid play_icon="hover"]</code></pre>
                    <p>Set the <code>play_icon</code> option to display a play icon on top of the thumbnail.</p>
                    <p>(Ignored unless <code>thumbnail</code> is set.)</p>
                    <p>Options are:</p>
                    <ul>
                        <li>
                            <p>
                                <code>none</code> (Default)
                            </p>
                        </li>
                        <li>
                            <p>
                                <code>always</code>
                            <p>
                        </li>
                        <li>
                            <p>
                                <code>hover</code> (When cursor is over the video.)
                            </p>
                            <p>
                        </li>
                    </ul>

                    <h5 class="wp-heading-inline">Lightbox</h5>
                    <pre><code>[ls-vid lightbox]</code></pre>
                    <p>Include the <code>lightbox</code> option to cause the video to be played in a lightbox when clicked.</p>
                    <p>(Ignored unless <code>thumbnail</code> is set.)</p>

                    <h5 class="wp-heading-inline">Width</h5>
                    <pre><code>[ls-vid width="1280"]</code></pre>
                    <p>Use the <code>width</code> to specifcy a size for the box containing the video. The box will always be in a 16x9 aspect ratio, so there is no need to set a height.</p>
                    <p>Supports standard sizing types: <code>px</code>, <code>em</code>, <code>rem</code>, <code>%</code>, etc.</p>

                    <h5 class="wp-heading-inline">Effect</h5>
                    <pre><code>[ls-vid effect="perspective"]</code></pre>
                    <p>Use the <code>effect</code> option to choose a visual effect applied to the video frame.</p>
                    <p>Options are:</p>
                    <ul>
                        <li>
                            <p>
                                <code>expand</code>
                            <p>
                        </li>
                        <li>
                            <p>
                                <code>none</code> (Default)
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <h4 class="title">LS Gallery</h4>
                    <pre><code>[ls-gallery]</code></pre>
                    <p>Use this shortcode to embed a grid of videos. Use the options below to customize the gallery.</p>

                    <h5 class="wp-heading-inline">Tags</h5>
                    <pre><code>[ls-vid tag="tag_one,tag_two,tag_three"]</code></pre>
                    <p>Use the <code>tag</code> option and supply a comma-separated list of tags to select which videos to display.</p>
                    <p>(If omitted all videos shown.)</p>

                    <h5 class="wp-heading-inline">Tag Picker</h5>
                    <pre><code>[ls-vid tag_picker]</code></pre>
                    <p>Include the <code>tag_picker</code> option to display buttons that the user can use to filter videos by the tags selected above.</p>
                    <p>(Ignored unless <code>tags</code> is set.)</p>

                    <h5 class="wp-heading-inline">Thumbnail</h5>
                    <pre><code>[ls-vid thumbnail]</code></pre>
                    <p>Include the <code>thumbnail</code> option to display the thumbnail from the video site on each video, rather than the embedded video frame. Clicking on the thumbnail will then load the video frame, which will automatically play.</p>

                    <h5 class="wp-heading-inline">Title</h5>
                    <pre><code>[ls-vid title="hover"]</code></pre>
                    <p>Set the <code>title</code> option to display the video's title on top of the thumbnail for each video.</p>
                    <p>(Ignored unless <code>thumbnail</code> is set.)</p>
                    <p>Options are:</p>
                    <ul>
                        <li>
                            <p>
                                <code>none</code> (Default)
                            </p>
                        </li>
                        <li>
                            <p>
                                <code>always</code>
                            <p>
                        </li>
                        <li>
                            <p>
                                <code>onhover</code>(When cursor is over the video.)
                            </p>
                        </li>
                    </ul>

                    <h5 class="wp-heading-inline">Play Icon</h5>
                    <pre><code>[ls-vid play_icon="hover"]</code></pre>
                    <p>Set the <code>play_icon</code> option to display a play icon on top of the thumbnail for each video.</p>
                    <p>(Ignored unless <code>thumbnail</code> is set.)</p>
                    <p>Options are:</p>
                    <ul>
                        <li>
                            <p>
                                <code>none</code> (Default)
                            </p>
                        </li>
                        <li>
                            <p>
                                <code>always</code>
                            <p>
                        </li>
                        <li>
                            <p>
                                <code>hover</code> (When cursor is over the video.)
                            </p>
                            <p>
                        </li>
                    </ul>

                    <h5 class="wp-heading-inline">Lightbox</h5>
                    <pre><code>[ls-vid lightbox]</code></pre>
                    <p>Include the option <code>lightbox</code> to cause each video to be played in a lightbox when clicked.</p>
                    <p>(Ignored unless <code>thumbnail</code> is set.)</p>

                    <h5 class="wp-heading-inline">Width</h5>
                    <pre><code>[ls-vid width="1280"]</code></pre>
                    <p>Use the <code>width</code> to specifcy a size for the box containing each video. The box will always be in a 16x9 aspect ratio, so there is no need to set a height.</p>
                    <p>Supports standard sizing types: <code>px</code>, <code>em</code>, <code>rem</code>, <code>%</code>, etc.</p>

                    <h5 class="wp-heading-inline">Effect</h5>
                    <pre><code>[ls-vid effect="perspective"]</code></pre>
                    <p>Use the <code>effect</code> option to choose a visual effect applied to each video frame.</p>
                    <p>Options are:</p>
                    <ul>
                        <li>
                            <p>
                                <code>expand</code>
                            <p>
                        </li>
                        <li>
                            <p>
                                <code>none</code> (Default)
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>