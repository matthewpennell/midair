<?= $this->extend('default') ?>

<?= $this->section('content') ?>
    <?= $content ?>
    <div id="loading" hx-get="/?p=<?= $next_page ?>" hx-trigger="revealed" hx-swap="outerHTML">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="30" height="30" style="shape-rendering: auto; display: block; background: transparent;" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g>
                <rect fill="#4dd4bb" height="25" width="25" y="7.5" x="7.5">
                    <animate calcMode="discrete" begin="0s" repeatCount="indefinite" dur="1s" keyTimes="0;0.125;1" values="#33746f;#4dd4bb;#4dd4bb" attributeName="fill"></animate>
                </rect>
                <rect fill="#4dd4bb" height="25" width="25" y="7.5" x="37.5">
                    <animate calcMode="discrete" begin="0.125s" repeatCount="indefinite" dur="1s" keyTimes="0;0.125;1" values="#33746f;#4dd4bb;#4dd4bb" attributeName="fill"></animate>
                </rect>
                <rect fill="#4dd4bb" height="25" width="25" y="7.5" x="67.5">
                    <animate calcMode="discrete" begin="0.25s" repeatCount="indefinite" dur="1s" keyTimes="0;0.125;1" values="#33746f;#4dd4bb;#4dd4bb" attributeName="fill"></animate>
                </rect>
                <rect fill="#4dd4bb" height="25" width="25" y="37.5" x="7.5">
                    <animate calcMode="discrete" begin="0.875s" repeatCount="indefinite" dur="1s" keyTimes="0;0.125;1" values="#33746f;#4dd4bb;#4dd4bb" attributeName="fill"></animate>
                </rect>
                <rect fill="#4dd4bb" height="25" width="25" y="37.5" x="67.5">
                    <animate calcMode="discrete" begin="0.375s" repeatCount="indefinite" dur="1s" keyTimes="0;0.125;1" values="#33746f;#4dd4bb;#4dd4bb" attributeName="fill"></animate>
                </rect>
                <rect fill="#4dd4bb" height="25" width="25" y="67.5" x="7.5">
                    <animate calcMode="discrete" begin="0.75s" repeatCount="indefinite" dur="1s" keyTimes="0;0.125;1" values="#33746f;#4dd4bb;#4dd4bb" attributeName="fill"></animate>
                </rect>
                <rect fill="#4dd4bb" height="25" width="25" y="67.5" x="37.5">
                    <animate calcMode="discrete" begin="0.625s" repeatCount="indefinite" dur="1s" keyTimes="0;0.125;1" values="#33746f;#4dd4bb;#4dd4bb" attributeName="fill"></animate>
                </rect>
                <rect fill="#4dd4bb" height="25" width="25" y="67.5" x="67.5">
                    <animate calcMode="discrete" begin="0.5s" repeatCount="indefinite" dur="1s" keyTimes="0;0.125;1" values="#33746f;#4dd4bb;#4dd4bb" attributeName="fill"></animate>
                </rect>
                <g></g>
            </g>
        </svg>
    </div>
<?= $this->endSection() ?>
