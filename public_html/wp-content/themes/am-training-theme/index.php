<?php
$static_home = ABSPATH . 'index.html';

if (is_file($static_home)) {
  $content = file_get_contents($static_home);

  if ($content !== false) {
    $base_tag = '<base href="' . esc_url(home_url('/')) . '">';
    $visibility_fallback = '<style id="am-static-visibility-fallback">body,.elementor,.elementor *{visibility:visible !important;opacity:1 !important;}body{background:#fff !important;color:#111 !important;}</style>';

    // Keep the original layout/styles but disable script execution to prevent blank-screen failures.
    $content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content);
    $content = preg_replace('/<script\b[^>]*>/i', '', $content);
    $content = preg_replace('/<\/script>/i', '', $content);

    if (stripos($content, '<head>') !== false) {
      $content = preg_replace('/<head>/i', '<head>' . "\n" . $base_tag . "\n" . $visibility_fallback, $content, 1);
    }

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
