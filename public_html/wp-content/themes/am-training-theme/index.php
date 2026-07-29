<?php
$static_home = ABSPATH . 'index.html';

if (is_file($static_home)) {
  $content = file_get_contents($static_home);

  if ($content !== false) {
    $base_tag = '<base href="' . esc_url(home_url('/')) . '">';
    $fallback_style = '<style id="am-static-fallback">body,.elementor,.elementor *{visibility:visible !important;opacity:1 !important;} .elementor-sticky__spacer{visibility:visible !important;} body{background:#fff !important;}</style>';

    // Avoid stale tunnel/browser cache during rapid homepage iterations.
    nocache_headers();

    // Deterministic render mode: keep markup/CSS, disable all script execution.
    $content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content);
    $content = preg_replace('/<script\b[^>]*>/i', '', $content);
    $content = preg_replace('/<\/script>/i', '', $content);

    // Unhide elements that were expected to be revealed by JavaScript.
    $content = preg_replace('/visibility\s*:\s*hidden/i', 'visibility: visible', $content);

    if (stripos($content, '<head>') !== false) {
      $content = preg_replace('/<head>/i', '<head>' . "\n" . $base_tag . "\n" . $fallback_style, $content, 1);
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
