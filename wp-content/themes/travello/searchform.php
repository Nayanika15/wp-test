<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */
?>
<aside class="single_sidebar_widget search_widget">
    <form role="search" method="get" class="searchform group" action="<?php echo home_url( '/' ); ?>" >
        <div class="form-group">
            <div class="input-group mb-3">
                <input type="search" class="form-control" placeholder="<?php echo esc_attr_x('Search', 'placeholder'); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x('Search for:', 'label'); ?>" />
                <div class="input-group-append">
                    <button class="btn" type="button"><i class="ti-search"></i></button>
                </div>
            </div>
        </div>
        <button class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn"
            type="submit">Search</button>
    </form>
</aside>