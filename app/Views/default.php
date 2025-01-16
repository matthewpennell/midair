<!DOCTYPE html>
<html lang="en">
    <head>
        <title>matthewpennell.com</title>
        <style>
            body {
                margin: 20px;
            }
            nav ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }
            nav ul li {
                display: inline;
                margin-right: 10px;
            }
            article {
                padding: 20px;
                border-bottom: 1px solid grey;
            }
            footer {
                clear: both;
                margin: 20px;
            }
        </style>
        <script src="https://unpkg.com/htmx.org@2.0.4"></script>
    </head>
    <body hx-boost="true">
        <?= $this->include('partials/navigation') ?>
        <?= $this->renderSection('content') ?>
        <?= $this->include('partials/footer') ?>
    </body>
</html>