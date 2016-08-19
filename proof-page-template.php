<?php
/**
 * The template for displaying proofs
 *
 */
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="utf-8" />
<title><?php wp_title(''); ?></title>

	<!-- Set the viewport width to device width for mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
    <!-- load main css stylesheet -->
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
    
    

<?php wp_head(); ?>

</head>

     
<?php while ( have_posts() ) : the_post(); 
$capImage = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()))?>
<body <?php body_class(); ?> style="background-image: url('<?php echo $capImage ?>');">

		<?php 
		// End the loop.
		endwhile;
		rewind_posts();
		?>

<div class="container-out"> 

    <div class="container">  
     

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			// Include the page content template.
			?>
			<div class='comments'>
				<div class='wrap'>
				<a class='go-button' target="_blank" href='<?php echo get_post_meta(get_the_ID(), 'url', true) ?>'>Visit Page</a>
				<h3 class='title'>
					<?php the_title(); ?>
				</h3>
				<div class='body'>
					<?php the_content(); ?>
				</div>
				<div class='nav'>
					<?php previous_post_link('%link &laquo;') ?>
					<?php next_post_link('%link ') ?>
				</div>
			</div>
			</div>

			<?php

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		// End the loop.
		endwhile;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
	</div><!-- .site-content -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>
