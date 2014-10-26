<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package _n
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			&copy; 2014 <?php bloginfo('name'); ?>
			<span class="sep"> | </span>
			alle Rechte vorbehalten 
			<?php 
			    $page = get_page_by_title( 'Impressum' );

		    	if ( isset($page) && !get_post_status($page->ID) == 'trash') {
		        	?> | <a href="<?php echo get_page_link($page->ID); ?>">Impressum</a><?php
		        } 
	        ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
</div><!-- .container -->

<?php wp_footer(); ?>

</body>
</html>