<?php
$shareUrl = urlencode(current_url());
$shareTitle = urlencode($data->title);
?>
<div class="share">
    <ul class="share__links">
        <li>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">
                <svg height="32" width="32" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="12" cy="12" r="12" class="black"/>
                    <path d="M14.5 5.5H13a2 2 0 00-2 2v1.5H9.5V11H11v6h2.5v-6H15l.5-2H13.5V7.75c0-.41.34-.75.75-.75h.75V5.5z" class="white"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on X (Twitter)">
                <svg height="32" width="32" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="12" cy="12" r="12" class="black"/>
                    <path d="M13.15 11.1L17.5 6h-1.05l-3.77 4.38L9.8 6H6.5l4.56 6.63L6.5 18h1.05l3.98-4.63L14.2 18h3.3l-4.35-6.9zm-1.4 1.63-.46-.66L7.9 6.8h1.58l2.97 4.24.46.66 3.85 5.5h-1.56l-3.45-4.57z" class="white"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $shareUrl ?>&title=<?= $shareTitle ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn">
                <svg height="32" width="32" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="12" cy="12" r="12" class="black"/>
                    <path d="M8.5 10h-2v7h2v-7zm-1-3.5a1.25 1.25 0 110 2.5 1.25 1.25 0 010-2.5zM17.5 13.25c0-1.8-1.45-3.25-3.25-3.25-.9 0-1.71.37-2.25.96V10h-2v7h2v-4.25a1.5 1.5 0 013 0V17h2v-3.75z" class="white"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="mailto:?subject=<?= $shareTitle ?>&body=<?= $shareUrl ?>" aria-label="Share via email">
                <svg height="32" width="32" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="12" cy="12" r="12" class="black"/>
                    <path d="M18 7.5H6a1 1 0 00-1 1v7a1 1 0 001 1h12a1 1 0 001-1v-7a1 1 0 00-1-1zm0 2.25L12 13.5 6 9.75V8.75l6 3.75 6-3.75v1z" class="white"/>
                </svg>
            </a>
        </li>
    </ul>
</div>
