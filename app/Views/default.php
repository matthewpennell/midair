<!DOCTYPE html>
<html lang="en">
    <head>
        <title>matthewpennell.com</title>
        <style>
            nav ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }
            nav ul li {
                display: inline;
                margin-right: 10px;
            }
            footer {
                clear: both;
                margin: 20px;
            }
        </style>
    </head>
    <body>
        <?= $this->include('partials/navigation') ?>
        <?= $this->renderSection('content') ?>
        <?= $this->include('partials/footer') ?>
    </body>
</html>