<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mi Custom Theme</title>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
</head>
<body>
    
    <h1>🎉 ¡Mi primer custom theme!</h1>
    
    <p>Hola, este es mi theme hecho desde cero.</p>
    
    <p>Nombre del sitio: <strong><?php bloginfo('name'); ?></strong></p>
    
    <p>URL: <?php echo home_url(); ?></p>
    
</body>
</html>