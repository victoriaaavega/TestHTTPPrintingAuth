<?php
$startTime = microtime(true);

require_once get_template_directory() . '/includes/ExperimentRunner.php';

$database = new Database();
$database->maybeCreateTable();

$runner = new ExperimentRunner(new SimulatorAdapter());

$heroExperiment   = $runner->run('experiment_hero');
$navbarExperiment = $runner->run('experiment_navbar');

$heroVariant   = $heroExperiment['variant'];
$navbarVariant = $navbarExperiment['variant'];
$visitorId     = $heroExperiment['visitorId'];
$heroSource    = $heroExperiment['source'];
$navbarSource  = $navbarExperiment['source'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>

    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/<?php echo $heroVariant === 'control' ? 'variant-control' : 'variant-b'; ?>.css">
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
            <a href="#">Log in</a>
        <?php else: ?>
            <a href="#">Sign up for free!!</a>
        <?php endif; ?>
    </nav>

    <div class="debug-info">
        <p>Visitor ID: <code><?php echo $visitorId; ?></code></p>
        <p>Experiment Hero → Variant: <code><?php echo $heroVariant; ?></code> | Source: <code><?php echo $heroSource; ?></code></p>
        <p>Experiment Navbar → Variant: <code><?php echo $navbarVariant; ?></code> | Source: <code><?php echo $navbarSource; ?></code></p>
    </div>

    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/heap-sync.js"></script>

    <?php
    $endTime = microtime(true);
    echo '<p style="color:black;padding:10px;">';
    echo 'Total: '          . round(($endTime - $startTime) * 1000) . 'ms<br>';
    ?>

</body>
</html>