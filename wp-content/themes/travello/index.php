<!--================Blog Area =================-->
<?php get_header(); 
$archive_title    = '';
    $archive_subtitle = '';
    if ( is_search() ):
        global $wp_query;
        $archive_title = sprintf(
            '%1$s %2$s',
            '<span class="color-accent">' . __( 'Search:', 'twentytwenty' ) . '</span>',
            '&ldquo;' . get_search_query() . '&rdquo;'
        );

        if ( $wp_query->found_posts ) { 

            $archive_subtitle = sprintf(
                /* translators: %s: Number of search results */
                _n(
                    'We found %s result for your search.',
                    'We found %s results for your search.',
                    $wp_query->found_posts),
                number_format_i18n( $wp_query->found_posts )
            );
        } else {
            $archive_subtitle = __( 'We could not find any results for your search. You can give it another try through the search form below.', 'twentytwenty' );
        }
    endif; ?><!-- .archive-header -->
    <section class="blog_area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="story_heading">
                        <?php if ( $archive_title ) { ?>
                            <h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>
                        <?php } ?>
                        <?php if ( $archive_subtitle ) { ?>
                            <h2 class="archive-title"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></h2>
                        <?php } ?>
                    
                    </div>
                    <div class="blog_left_sidebar">
                        <?php
                            if ( have_posts() ) :
                                while ( have_posts() ) : the_post(); ?>
                                    <article <?php post_class(); ?> id="post-<?php the_ID(); ?>" class="blog_item" >
                                        <div class="blog_item_img">
                                            <?php 
                                            if ( has_post_thumbnail() ):
                                                the_post_thumbnail( 'featured-large', array( 'class' => 'card-img rounded-0' ) );
                                            else:?>
                                                <img class="card-img rounded-0" src="<?php bloginfo('template_directory'); ?>/assets/img/blog/single_blog_1.png" alt="<?php the_title(); ?>" />
                                            <?php 
                                            endif;
                                            ?>
                                            <a href="#" class="blog_item_date">
                                                <h3><?php _e( get_the_date('d') ); ?></h3>
                                                <p><?php _e( get_the_date('M') ); ?></p>
                                            </a>
                                        </div>

                                        <div class="blog_details">
                                            <a class="d-inline-block" href="<?php the_permalink() ?>">
                                                <h2><?php the_title() ?></h2>
                                            </a>
                                            <?php the_excerpt() ?>
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
                                        </div>
                                    </article>
                                <?php endwhile;
         
                            else :
                                _e('There are no posts!');    
                            endif;
                        ?>
                    </div>
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
    <!--================Blog Area =================-->
<?php
    get_footer();