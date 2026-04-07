<?php

$runner = abtf_runner();

$heroExperiment = $runner->run('Talent Version');

$heroVariant = $heroExperiment['variant'];
$visitorId   = $heroExperiment['visitorId'];
$heroSource  = $heroExperiment['source'];

// navbar usa el mismo resultado porque es el mismo flag
$navbarVariant = $heroVariant;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>

    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/<?php echo $heroVariant === 'control' ? 'variant-control' : 'variant-b'; ?>.css">

    <?php wp_head(); ?>
</head>
<body>

    <?php
    if ($heroVariant === 'control') {
        require get_template_directory() . '/templates/variant-control.php';
    } else {
        require get_template_directory() . '/templates/variant-b.php';
    }
    ?>

    <nav class="navbar">
        <?php if ($navbarVariant === 'control'): ?>
            <a href="#" id="navbar-cta">Log in</a>
        <?php else: ?>
            <a href="#" id="navbar-cta">Sign up for free!</a>
        <?php endif; ?>
    </nav>

    <div class="debug-info">
        <p>Visitor ID: <code><?php echo esc_html($visitorId); ?></code></p>
        <p>Hero → Variant: <code><?php echo esc_html($heroVariant); ?></code> | Source: <code><?php echo esc_html($heroSource); ?></code></p>
    </div>

    <script>
        window.abTestData = {
            visitorId: "<?php echo esc_js($visitorId); ?>",
            experiments: {
                "Talent Version": "<?php echo esc_js($heroVariant); ?>"
            }
        };

        window.abTestConfig = [
            {
                experimentId: "Talent Version",
                selector: ".hero-button",
                eventName: "hero_cta_click",
                type: "click"
            },
            {
                experimentId: "Talent Version",
                selector: "#navbar-cta",
                eventName: "navbar_cta_click",
                type: "click"
            }
        ];
    </script>

    <?php wp_footer(); ?>
</body>
</html>