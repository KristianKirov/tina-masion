<?php
if (isset($collection_media_params)) :
?>
<div class="hover-wrapper">
<?php
    $collection_url = $collection_media_params['collection_url'];
    $video_field = $collection_media_params['video_field'];
    $image_field = $collection_media_params['image_field'];
    $media_orientation = $collection_media_params['orientation'];

    $collection_image_url = get_field($image_field);
    if ($collection_image_url):
?>
    <img class="responsive" src="<?php echo $collection_image_url; ?>" />
<?php
    else:
        $collection_video = get_field($video_field);
        if ($collection_video):
            $collection_video_id = $collection_video['vid'];
?>
            <div class="js-youtubevideo" data-id="<?php echo $collection_video_id; ?>" data-orientation="<?php echo $media_orientation; ?>"></div>
<?php
        endif;
    endif;
?>
<a href="<?php echo $collection_url; ?>" class="hover-link hover-link--<?php echo $media_orientation; ?> container-center"><span class="content-centered">View</span></a>
</div>
<?php
endif; ?>