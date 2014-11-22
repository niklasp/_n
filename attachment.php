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
						$attachment[] = wp_prepare_attachment_for_js( $post->id );
					  	
						//var_dump($attachment[0]);
						$image_title = $attachment[0]['title'];
						$caption = $attachment[0]['caption'];
						//$description = $image->post_content;
						

						?>
						<div class="attachment-container">
							<nav id="image-navigation" class="navigation image-navigation">
							                <div class="nav-links">
							    			<?php previous_image_link( false, '<div class="previous-image">' . __( '<', '$text_domain' ) . '</div>' ); ?>            
							                <?php next_image_link( false, '<div class="next-image">' . __( '>', '$text_domain' ) . '</div>' ); ?>
							                </div><!-- .nav-links -->
									</nav><!-- #image-navigation -->							
							<div class="attachment">
								
								<img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>"  class="attachment-medium" alt="<?php $post->post_excerpt; ?>" />
								
								<p><b><?php echo $image_title . '</b> '; ?><?php if(!empty($caption)) echo ', ' . $caption; ?></p>
							</div>
					</div>

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