<?php
/**
 * Template part for displaying posts
 *
 * @package ViroMarket
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>');
        endif;
        ?>
        
        <div class="entry-meta">
            <span class="posted-on">
                <i data-lucide="calendar"></i>
                <?php echo get_the_date(); ?>
            </span>
            <span class="byline">
                <i data-lucide="user"></i>
                <?php the_author(); ?>
            </span>
            <?php if (has_category()) : ?>
                <span class="cat-links">
                    <i data-lucide="folder"></i>
                    <?php the_category(', '); ?>
                </span>
            <?php endif; ?>
        </div>
    </header>
    
    <div class="entry-content">
        <?php
        if (is_singular()) :
            the_content();
            
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'viromarket'),
                'after'  => '</div>',
            ));
        else :
            the_excerpt();
            ?>
            <a href="<?php the_permalink(); ?>" class="read-more">
                <?php esc_html_e('Read More', 'viromarket'); ?>
                <i data-lucide="arrow-right"></i>
            </a>
            <?php
        endif;
        ?>
    </div>
    
</article>
