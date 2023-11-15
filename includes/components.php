<?php

function youtube_player(string $yt_id) {
?>
    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $yt_id; ?>?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%;"></iframe>
<?php
}


function vimeo_player(string $vimeo_id) {
?>
    <iframe src="https://player.vimeo.com/video/<?php echo $vimeo_id; ?>?autoplay=1&loop=1&autopause=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%;"></iframe>
<?php
}
