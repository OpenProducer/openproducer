
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
                    <title>Database Connection Issue</title>
                </head>
                <body class="wp-dropin db-error">
                    <main class="message">
                        <h1 class="message__title">Something&apos;s come undone.</h1>
                        <p class="message__text">Sorry, this page can&apos;t be reached because of a database connection issue.</p>
                    </main>
                    <footer class="resolution">
                        <p>If this is your website, you can <a href="https://codex.wordpress.org/Common_WordPress_Errors#Error_Establishing_Database_Connection">read more</a> about the issue or <a href="https://my.bluehost.com/hosting/help">contact support</a> to get help.</p>
                    </footer>
                </body>
            </html>
        