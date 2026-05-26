<?php
// フォールバック。通常は front-page.php / archive.php 等が優先される
get_header();
?>
<main class="l-main">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
	<?php endwhile; endif; ?>
</main>
<?php
get_footer();
