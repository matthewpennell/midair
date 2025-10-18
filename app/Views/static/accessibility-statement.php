<?= $this->extend('default') ?>

<?= $this->section('title') ?>Accessibility statement<?= $this->endSection() ?>
<?= $this->section('og-title') ?>Accessibility statement<?= $this->endSection() ?>
<?= $this->section('description') ?>Statement of accessibility for matthewpennell.com<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content">
        <header class="title">
            <h1>Accessibility statement</h1>
        </header>
        <article>
            <div class="content__body">
                <h2>Conformance status</h2>
                <p>The Web Content Accessibility Guidelines (WCAG) defines requirements for designers and developers to improve accessibility for people with disabilities. It defines three levels of conformance: Level A, Level AA, and Level AAA. matthewpennell.com is fully conformant with WCAG 2.2 level AA. Fully conformant means that the content fully conforms to the accessibility standard without any exceptions.</p>
                <h2>Feedback</h2>
                <p>I welcome any feedback on the accessibility of this site; please let me know if you encounter accessibility barriers on matthewpennell.com by getting in touch <a href="https://bsky.app/profile/matthewpennell.com">on social media at Bluesky: @matthewpennell.com</a>.</p>
                <h2>Assessment approach</h2>
                <p>The accessibility of the site was assessed by self-evaluation.</p>
                <p>This statement was created on 13 September 2025 with the help of the <a href="https://www.w3.org/WAI/planning/statements/" target="_blank" rel="noopener noreferrer">W3C Accessibility Statement Generator Tool</a>.</p>
            </div>
        </article>
    </div>
<?= $this->endSection() ?>
