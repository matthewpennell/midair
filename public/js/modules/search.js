/**
 * Search highlighting functionality
 */

export function highlightSearchTerms() {
    const query = document.getElementById('query');
    if (!query) return;

    // Check for CSS Custom Highlight API support
    if (!CSS.highlights) return;

    // Clean-up the search query and bail-out if it's empty
    const searchTerm = query.textContent.trim().toLowerCase();
    if (!searchTerm) return;

    const article = document.querySelector('.layout__pane--content');
    if (!article) return;

    // Find all text nodes in the article
    const treeWalker = document.createTreeWalker(article, NodeFilter.SHOW_TEXT);
    const allTextNodes = [];
    let currentNode = treeWalker.nextNode();
    
    while (currentNode) {
        allTextNodes.push(currentNode);
        currentNode = treeWalker.nextNode();
    }

    // Clear any existing highlights
    CSS.highlights.clear();

    // Find all matches in text nodes
    const ranges = allTextNodes
        .map((node) => ({
            node,
            text: node.textContent.toLowerCase()
        }))
        .flatMap(({ node, text }) => {
            const ranges = [];
            let startPos = 0;
            let index;
            
            while ((index = text.indexOf(searchTerm, startPos)) >= 0) {
                const range = new Range();
                range.setStart(node, index);
                range.setEnd(node, index + searchTerm.length);
                ranges.push(range);
                startPos = index + searchTerm.length;
            }
            
            return ranges;
        });

    // Create and register the highlight
    if (ranges.length > 0) {
        const searchResultsHighlight = new Highlight(...ranges);
        CSS.highlights.set("search-results", searchResultsHighlight);
    }
}
