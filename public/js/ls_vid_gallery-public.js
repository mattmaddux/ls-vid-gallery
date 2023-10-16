function toggleVideo($video_id) {
	var videoWrapper = document.getElementById("wrapper_" + $video_id);
	var videoThumb = document.getElementById("thumb_" + $video_id);
	var overlay = document.getElementById("overlay_" + $video_id);
	var hover_overlay = document.getElementById("hover_overlay_" + $video_id);
	var videoEmbed = document.getElementById("embed_" + $video_id);
	var lightbox = document.getElementById("lightbox_" + $video_id);

	if (lightbox) {
		if (lightbox.classList.contains("hidden")) {
			if (videoWrapper.classList.contains("expand")) {
				videoWrapper.classList.remove("expand");
				videoWrapper.classList.add("expand-hold");
			}
			lightbox.classList.remove("hidden");
		} else {
			lightbox.classList.add("hidden");
			if (videoWrapper.classList.contains("expand-hold")) {
				videoWrapper.classList.remove("expand-hold");
				videoWrapper.classList.add("expand");
			}
		}
		return;
	}

	if (videoThumb) {
		if (videoEmbed.classList.contains("hidden")) {
			videoEmbed.classList.remove("hidden");
			videoThumb.classList.add("hidden");
			if (overlay) { overlay.classList.add("hidden"); }
			if (hover_overlay) { hover_overlay.classList.add("hidden"); }
		} else {
			videoEmbed.classList.add("hidden");
			videoThumb.classList.remove("hidden");
			if (overlay) { overlay.classList.remove("hidden"); }
			if (hover_overlay) { hover_overlay.classList.remove("hidden"); }
		}
	}
}


function toggleTag($slug) {
	console.log($slug);
	var selectedSlug = $slug;
	var clickedButton = document.getElementById("button_" + $slug);
	var activeButtons = document.getElementsByClassName("tag_button active");


	if (clickedButton.classList.contains("active")) {
		clickedButton.classList.remove("active");
		selectedSlug = null;
	} else {
		if (activeButtons.length > 0) {
			activeButton = activeButtons[0];
			activeButton.classList.remove("active");
		}
		clickedButton.classList.add("active");
	}

	var allVids = document.getElementsByClassName("video_wrapper");
	for (vid of allVids) {
		if (selectedSlug == null) {
			if (vid.classList.contains("hidden")) { vid.classList.remove("hidden"); }
			continue;
		}

		var vidTags = vid.getAttribute("data-tags");
		if (vidTags.includes(selectedSlug)) {
			if (vid.classList.contains("hidden")) { vid.classList.remove("hidden"); }
		} else {
			if (!vid.classList.contains("hidden")) { vid.classList.add("hidden"); }
		}
	};

}

function sizeOverlays() {
	console.log("Sizing overlays");
	var allVids = document.getElementsByClassName("video_wrapper");
	for (vidWrapper of allVids) {
		var wrapIterable = vidWrapper;
		var height = vidWrapper.offsetHeight;

		var play_icons = vidWrapper.getElementsByClassName("play_icon_span");
		var iconSize = Math.round(height * 0.3);
		for (var i = 0; i < play_icons.length; i++) {
			var icon = play_icons[i];
			icon.style.fontSize = iconSize + "px";
			icon.style.width = iconSize + "px";
			icon.style.height = iconSize + "px";
		}

		var titles = vidWrapper.getElementsByClassName("video_title");
		var titleHeight = Math.round(height * 0.1);
		for (var i = 0; i < titles.length; i++) {
			var title = titles[i];
			title.style.fontSize = titleHeight + "px";
		}
	}
}

window.onload = sizeOverlays;