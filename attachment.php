<?php
/**
 * The Template for displaying all single posts.
 *
 * @package _n
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if (wp_attachment_is_image($post->id)) {
						
		$att_image = wp_get_attachment_image_src( $post->id, array(700,570));
		$att_image_large = wp_get_attachment_image_src( $post->id, array(1500,1500));
		$attachment[] = wp_prepare_attachment_for_js( $post->id );
	  	
		$image_title = $attachment[0]['title'];
		$caption = $attachment[0]['caption'];
		//$description = $image->post_content;
		$style = get_theme_mod('_n_single_image_style','center');

		$content = get_post_field('post_content', $post->post_parent);
		$result;
		preg_match_all('/\[gallery.*ids="(.*?)"/', $content, $ids, PREG_SET_ORDER);
		foreach ($ids as $k => $the_ids) {
			$arr = explode(",",$the_ids[1]);
			if (in_array($post->ID, $arr)) {
				$result = $arr;
				break;
			}
		}

		$parent =  $post->post_parent;

		$current = array_search(get_the_ID(), $arr);
		$prevID = $arr[$current-1];
		$nextID = $arr[$current+1];

		$next_url = isset($nextID) ? get_permalink($nextID) : NULL;
		$prev_url = isset($prevID) ? get_permalink($prevID) : NULL;

		$image = '<img data-large-img="' . $att_image_large[0] .'" src="' . $att_image[0] . '" width="' . $att_image[1] . '" height="' . $att_image[2] . '"  class="attachment-medium" alt="' . $post->post_excerpt .' " />';
		if (get_theme_mod( '_n_single_image_zoom', false )) {
			$image = '<span id="n-zoom">' . $image . '</span>';
		}
		?>

		<?php if($style == 'left') { ?>
		<div class="attachment-container row hidden-xs">
			<div class="attachment-image col-xs-12 col-sm-9">
				<?php echo $image; ?>
			</div>
			<div class="attachment-label bottom col-xs-6 col-sm-3">
				<span><b><?php echo $image_title . '</b> '; ?><?php if(!empty($caption)) echo ', ' . $caption; ?></span>
			</div>
		</div> 

		<?php } elseif ($style == 'right') { ?>

		<div class="attachment-container row hidden-xs">
			<div class="attachment-label bottom col-xs-6 col-sm-3 text-right">
				<span><b><?php echo $image_title . '</b> '; ?><?php if(!empty($caption)) echo ', ' . $caption; ?></span>
			</div>
			<div class="attachment-image col-xs-12 col-sm-9 text-right">
				<?php echo $image; ?>
			</div>
		</div>	

		<?php } elseif ($style == 'center') {?>

		<div class="attachment-container center row hidden-xs">
			<div class="attachment-image col-xs-12">
				<div class="attachment-center-container">
				<?php echo $image; ?>
				<p><b><?php echo $image_title . '</b> '; ?><?php if(!empty($caption)) echo ', ' . $caption; ?></p>
				</div>
			</div>
		</div>

		<?php } ?>

		<!-- fallback for small screens -->
		<div class="attachment-container row visible-xs">
			<div class="attachment-image col-xs-12 ">
				<img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>" class="attachment-medium" alt="<?php $post->post_excerpt; ?>" />
			</div>
			<div class="attachment-label bottom col-xs-12">
				<span><b><?php echo $image_title . '</b> '; ?><?php if(!empty($caption)) echo ', ' . $caption; ?> awidn awiudnaiwund aiwudn aiwudn aiuwnd aiwund aiwund </span>
			</div>
		</div>
		<!-- image navigation -->
		<nav id="image-navigation" class="navigation image-navigation col-xs-12">
            <div class="nav-links">
            <?php if (isset($prev_url)) {?>
				<a href="<?php echo $prev_url ?>"><div class="previous-image"><</div></a>
			<?php } ?>
            <?php if (isset($next_url)) {?>
				<a href="<?php echo $next_url ?>"><div class="next-image">></div></a>
			<?php } ?>
            </div><!-- .nav-links -->
		</nav><!-- #image-navigation -->							
		<?php } ?>
		<?php 
		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
			the_post_thumbnail();
		} 
		?>		
		<?php the_content(); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>