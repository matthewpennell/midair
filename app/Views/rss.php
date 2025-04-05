<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <atom:link href="http://matthewpennell.com/rss/blog" rel="self" type="application/rss+xml" />
    <title><?= esc($title) ?></title>
    <link><?= esc('https://matthewpennell.com') ?></link>
    <description><?= esc($description) ?></description>
    <lastBuildDate><?= $last_build_date ?></lastBuildDate>
    <language>en-gb</language>
    <?php foreach ($items as $item): ?>
        <item>
            <title><?= esc($item->title) ?></title>
            <link><?= base_url($item->type . '/' . $item->url) ?></link>
            <description><![CDATA[<?= $item->excerpt ?>]]></description>
            <pubDate><?= date(DATE_RSS, strtotime($item->date)) ?></pubDate>
            <guid><?= base_url($item->type . '/' . $item->url) ?></guid>
        </item>
    <?php endforeach; ?>
</channel>
</rss>
