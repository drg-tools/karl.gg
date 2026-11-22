import "alpinejs";
import { trackViewModeChange, trackViewModeShown } from "./analytics";
import "./browse";

/**
 * Inline Alpine components (the loadout page's view toggle) call these. Alpine
 * waits for DOMContentLoaded, so this is set before any component starts.
 */
window.karlAnalytics = { trackViewModeChange, trackViewModeShown };
