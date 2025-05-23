<?php /** * Template Name: Full Width Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package newsup
 */

get_header(); 
get_template_part('index','banner'); ?>

<main id="content" class="fullwidth-class">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
		    <div class="mg-card-box padding-20"> <?php
          while ( have_posts() ) : the_post(); 

            if(has_post_thumbnail()) {
              echo'<figure class="post-thumbnail">';
                the_post_thumbnail( '', array( 'class'=>'img-responsive' ) );
              echo'</figure>';
            }
            the_content();
            newsup_edit_link();

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif;

          endwhile; // End of the loop. ?>
        </div>
      </div>
    </div>
  </div>
</main>
<?php get_footer();