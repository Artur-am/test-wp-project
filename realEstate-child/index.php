<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php if ( is_front_page() && is_home() ) : ?>
	<?php get_template_part( 'global-templates/hero' ); ?>
<?php endif; ?>

<div class="wrapper" id="index-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check and opens the primary div -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>
			<main class="site-main" id="main">

                <section class="row section-area">
                    <?php foreach (realEstate_get_terms('test_tags') as $term) : ?>
                        <div class="col-sm-6 mb-5">
                            <div class="card position-relative">
                                <a class="card__link position-relative" href="<?php echo get_term_link( $term, 'test_tags' );?>">
                                    <?php if( $img = get_field('img', $term) ): ?>
                                        <img class="card__img" src="<?php echo $img; ?>" alt="<?php echo $term->name; ?>" />
                                    <?php endif;?>
                                    <div class="card__meddile">
                                        <p class="c_label mb-3"><?php echo $term->count; ?></p>
                                        <p class="c_label mt-0"><?php echo $term->name; ?></p>
                                    </div>
                                    <div class="card__description">
                                        <?php echo $term->description; ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>


				<?php if(getPost::set_posts(array('post_type' => 'real_estate', 'posts_per_page' => 4))) : ?>

					<?php /* Start the Loop */ ?>
                    <section>
                        <h4 class="display-4 text-center"><?php echo realEstate_lang('Нерухомість'); ?></h4>
                        
                        <div class="row mt-5 js-section__post">
                            <?php while (getPost::get_post()) : ?>
                            <?php echo HTML_post_card(); ?>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                        <div class="form-row text-center">
                            <div class="col-12">
                                <button type="button" class="btn btn-secondary btn-lg js-more-posts" data-type="real_estate"><?php echo realEstate_lang('Еще'); ?></button>
                            </div>
                        </div>
                    </section>
				<?php else : ?>

					<?php get_template_part( 'loop-templates/content', 'none' ); ?>

                <?php endif; ?>

			</main><!-- #main -->

			<!-- The pagination component --> 
			<?php understrap_pagination(); ?>

			<!-- Do the right sidebar check -->
			<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #index-wrapper -->

<?php get_footer(); ?>
