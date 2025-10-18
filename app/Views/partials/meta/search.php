<div class="section">
    <div class="section-title">Search</div>
    <div class="section-subtitle">Results for your search for "<span id="query"><?= $q ?></span>" &#8594;</div>
</div>
<form action="/search" class="search-form">
    <input type="hidden" name="section" value="<?= $search ?>">
    <input type="text" name="q" placeholder="Search the archives...">
    <button type="submit">Search</button>
</form>
