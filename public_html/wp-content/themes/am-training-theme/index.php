<?php
$static_home = ABSPATH . 'index.html';

if (is_file($static_home)) {
  readfile($static_home);
  return;
}

get_header();
?>
<main>
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <?php the_content(); ?>
    <?php endwhile; ?>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
