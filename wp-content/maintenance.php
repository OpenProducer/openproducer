
            <?php ob_start();
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                header('Status: 503 Service Temporarily Unavailable');
                header('Retry-After: 3600'); // 1 hour = 3600 seconds
            ?>
            <!DOCTYPE html>
            <html lang="en-US">
                <head>
                    <meta charset="UTF-8" name="viewport" content="width=device-width", intial-scale="1.0">
                    <link rel="stylesheet" type="text/css" href="https://bluehost-cdn.com/media/user/bluerock/_bh/main.css">
                    <link rel="stylesheet" type="text/css" href="https://bluehost-cdn.com/media/user/bluerock/_bh/wp_dropins.css">
                    <title>Maintenance</title>
                </head>
                <body class="wp-dropin maintenance">
                    <main class="message">
                        <figure class="message__image"></figure>
                        <section class="message__block">
                            <h1 class="message__title">Maintenance Underway</h1>
                            <p class="message__text">This site is having some routine server maintenance. It should take less than a minute. </p>
                        </section>
                    </main>
                    <footer class="resolution">
                        <p>If this is your website and you see this page for more than a few minutes, please <a href="https://my.bluehost.com/hosting/help">contact support</a>. </p>
                    </footer>
                </body>
           </html>
        