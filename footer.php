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
	</div><!-- #page -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			&copy; 2014 <?php bloginfo('name'); ?>
			<span class="sep"> | </span>
			alle Rechte vorbehalten 
			<?php 
			    $page = get_page_by_title( 'Impressum' );

		    	if ( isset($page) && !(get_post_status($page->ID) == 'trash')) {
		        	?> | <a href="<?php echo get_page_link($page->ID); ?>">Impressum</a><?php
		        } 
	        ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- .container -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<?php wp_footer(); ?>

</body>
</html>