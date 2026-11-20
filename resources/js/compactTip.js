/** Space kept between a tooltip and the edge of the window, in pixels. */
const EDGE_GAP = 8;

/**
 * Nudges the hover tooltip inside a compact builder choice back onto the
 * screen. Tooltips are centred under their hex, so ones near the window edge
 * (tier option A, the first overclock column) would otherwise be cut off.
 * Bound to mouseenter and focus, when the tooltip has just become visible.
 *
 * @param {Event} event
 */
export function keepTipOnScreen(event) {
    const tip = event.currentTarget.querySelector(".compact-tip");
    if (!tip) {
        return;
    }
    tip.style.setProperty("--tip-shift", "0px");
    const { left, right, width } = tip.getBoundingClientRect();
    if (width === 0) {
        return;
    }
    const viewportWidth = document.documentElement.clientWidth;
    let shift = 0;
    if (left < EDGE_GAP) {
        shift = EDGE_GAP - left;
    } else if (right > viewportWidth - EDGE_GAP) {
        shift = viewportWidth - EDGE_GAP - right;
    }
    tip.style.setProperty("--tip-shift", `${Math.round(shift)}px`);
}
