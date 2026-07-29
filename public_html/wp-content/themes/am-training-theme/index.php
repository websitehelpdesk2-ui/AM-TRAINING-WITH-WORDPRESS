<?php
$static_home = ABSPATH . 'index.html';

if (is_file($static_home)) {
  $content = file_get_contents($static_home);

  if ($content !== false) {
    // Avoid stale tunnel/browser cache during rapid homepage iterations.
    nocache_headers();

    // Remove only fragile marketing tracker scripts known to break rendering in some clients.
    $content = preg_replace(
      '/<script\b[^>]*\bsrc="\.\/js\/(pixel\.js|insight\.min\.js|events\.js|identify_[^"\\s>]+\.js|main\.[^"\\s>]+\.js)"[^>]*><\/script>/i',
      '',
      $content
    );

    echo $content;
    return;
  }

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
