/**
 * Layout-related functionality
 */

export function setupListContainer() {
    function adjustListContainerHeight() {
        const listContainer = document.querySelector('.list__container');
        const hexagons = document.querySelectorAll('.hexagon');
        const lastHexagon = hexagons.length > 0 ? hexagons[hexagons.length - 1] : null;
        
        if (!listContainer || !lastHexagon) return;

        // Calculate the position of the last hexagon relative to the container
        const lastHexagonTop = lastHexagon.offsetTop;
        const containerTop = listContainer.offsetTop;
        
        // Calculate the height needed to contain all hexagons
        const height = lastHexagonTop - containerTop;
        
        // Set the container height
        listContainer.style.height = `${Math.max(0, height)}px`;
    }

    // Run on load and when the window is resized
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', adjustListContainerHeight);
    } else {
        adjustListContainerHeight();
    }
    
    window.addEventListener('resize', adjustListContainerHeight);
}
