<?php

require_once get_template_directory() . '/includes/experiment-runner.php';

$result    = runExperiment();
$variant   = $result['variant'];
$visitorId = $result['visitorId'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>

    <?php?>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">

    <?php?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/<?php echo $variant === 'control' ? 'variant-control' : 'variant-b'; ?>.css">
</head>
<body>

    <?php
    // Render the correct template based on the assigned variant
    if ($variant === 'control') {
        require get_template_directory() . '/templates/variant-control.php';
    } else {
        require get_template_directory() . '/templates/variant-b.php';
    }
    ?>

    <div class="debug-info">
        <p>Visitor ID: <code><?php echo $visitorId; ?></code></p>
        <p>Variant: <code><?php echo $variant; ?></code></p>
    </div>

    <?php?>
    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/heap-sync.js"></script>

</body>
</html>