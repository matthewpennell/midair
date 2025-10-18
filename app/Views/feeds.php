<?= $this->extend('default') ?>

<?= $this->section('title') ?>RSS<?= $this->endSection() ?>
<?= $this->section('og-title') ?>matthewpennell.com RSS feeds<?= $this->endSection() ?>
<?= $this->section('description') ?>RSS feed construction page for matthewpennell.com<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content">
        <header class="title">
            <h1>RSS feeds</h1>
        </header>
        <article>
            <div class="content__body">
                <p>Choose which of the available feed types you would like to subscribe to, and generate a custom RSS feed compatible with any RSS aggregator.</p>
                <form method="get" action="/rss">
                    <input type="hidden" name="types[]" value=""> <!-- Ensures empty array is sent if nothing checked -->
                    <fieldset>
                        <?php foreach ($feed_types as $type => $label): ?>
                            <label for="type_<?= $type ?>">
                                <input type="checkbox" name="types[]" id="type_<?= $type ?>" value="<?= $type ?>" checked>
                                <?= $label ?>
                            </label>
                        <?php endforeach; ?>
                    </fieldset>
                    <button type="submit">Generate feed</button>
                </form>
                <h2 data-v-7a1c44d3="">Or receive new posts by email</h2>
                <p>Don't do RSS? Sign up below to receive selected posts via email instead.</p>
                <div class="followit--follow-form-container" attr-a attr-b attr-c attr-d attr-e attr-f>
                    <form data-v-7a1c44d3="" id="followit" action="https://api.follow.it/subscription-form/eDZNS1J6MkUwWVIvZjBteGE2ZktvZUZHbVFZUDdyUzViV09KZm1EcFYveUVsOFlGaGZ2Qis3a1FWT2J6SWI1TVpnZmlTRWE0UUhwWEwrR2hDMEFXUlIvcUgwQXUxZC8xOXN1Vzlnb2VDM1NpYTFOWG1xZDBhZzVBYmcxN1hJKzh8QStpNklpM21jQ3MrU1JzRlNvbTB3aGw0QVVjdytKT1FOYmRGd25uYjVOMD0=/8" method="post">
                        <div data-v-7a1c44d3="" class="preview-input-field">
                            <label for="email">Email address:</label>
                            <input data-v-7a1c44d3="" id="email" type="email" name="email" required="" placeholder="Enter your email address" spellcheck="false">
                        </div>
                        <div data-v-7a1c44d3="" class="preview-submit-button">
                            <button data-v-7a1c44d3="" type="submit">Subscribe</button>
                            <a href="https://follow.it" class="powered-by-line" target="_blank" rel="noopener noreferrer">Powered by <img src="https://follow.it/images/colored-logo.svg" alt="follow.it" height="17px"/></a>
                        </div>
                    </form>
                </div>
            </div>
        </article>
    </div>
<?= $this->endSection() ?>
