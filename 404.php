
<?php
$_title = '404';

require_once(__DIR__.'/globals.php');
require_once(__DIR__.'/components/header.php');

echo "<div class='container middle'>
        <h1>404 Page not found</h1>
        <div class='link'>
            <a href='home'>Back to home</a>
        </div>
    </div>";

require_once(__DIR__.'/components/footer.php');
?>
