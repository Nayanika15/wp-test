<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

get_header();
?>

<main id="site-content" role="main">
<!--================Blog Area =================-->
   <section class="blog_area single-post-area section-padding">
      <div class="container">
         <div class="row">
            <div class="col-lg-8 posts-list">
            <?php if ( have_posts() ) : while ( have_posts() ) :
                the_post(); ?>
               <div class="single-post">
                  <div class="feature-img">
                   	<?php 
                    if ( has_post_thumbnail() ):
                        the_post_thumbnail( 'featured-large', array( 'class' => 'card-img rounded-0' ) );
                    else:?>
                        <img class="card-img rounded-0" src="<?php bloginfo('template_directory'); ?>/assets/img/blog/single_blog_1.png" alt="<?php the_title(); ?>" />
                    <?php 
                    endif;
                    ?>
                  </div>
                  <div class="blog_details">
                     <h2><?php the_title(); ?></h2>
                     <ul class="blog-info-link">
                        <?php $all_categories = get_the_category( $post->ID );
                        if( count($all_categories) > 0 ):
                            $category = array();
                            foreach ($all_categories as $categories):

                             array_push($category, $categories->name);
                            endforeach;
                        ?>
                        <li><a href="#"><i class="fa fa-user"></i> 
                         <?php _e( implode($category, ',') ); ?></a></li>
                <?php   endif;?>
                        <li><a href="#"><i class="fa fa-comments"></i> <?php _e(  get_comments_number($post->ID) );?> Comments</a></li>
                    </ul>
                     <p class="excert"> <?php the_content(); ?></p>
                  </div>
               </div>
          <?php /**
				 *  Output comments wrapper if it's a post, or if comments are open,
				 * or if there's a comment number – and check for password.
				 * */
				if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ):
				?>

					<div class="comments-wrapper section-inner">

						<?php comments_template(); ?>

					</div><!-- .comments-wrapper -->

				<?php endif;?>
               </div>
            <div class="col-lg-4">
                <div class="blog_right_sidebar">
                    <aside class="single_sidebar_widget search_widget">
                       <?php
                        get_search_form(
                            array(
                                'label' => __( 'search again', 'twentytwenty' ),
                            )
                        );
                        ?>
                    </aside>
                    <?php dynamic_sidebar( 'sidebar' ); ?>
                </div>
            </div>
         </div>
      </div>
   </section>
<?php endwhile;  endif; ?>

</main><!-- #site-content -->
<?php get_footer(); ?>
