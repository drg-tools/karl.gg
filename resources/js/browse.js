/**
 * Loadout browse page enhancements.
 *
 * The page works as a plain GET form without this file. With it, results
 * update as filters change (the server returns just the results fragment for
 * X-Requested-With requests), the URL stays in sync, pickers gain a search box
 * and are scoped to the chosen class, and the filters become a drawer on
 * narrow screens.
 */
const SEARCH_DELAY = 300;
const DEFAULT_SORT = 'updated';
const DRAWER_QUERY = '(max-width: 1023px)';

export function initBrowse(root) {
    const form = root.querySelector('[data-browse-form]');
    const body = root.querySelector('[data-browse-body]');
    const results = root.querySelector('[data-browse-results]');
    const drawer = root.querySelector('[data-browse-drawer]');
    const backdrop = root.querySelector('.browse-backdrop');
    const openButton = root.querySelector('[data-browse-open]');
    const sortSelect = root.querySelector('[data-browse-sort]');
    const searchInput = root.querySelector('[data-browse-search]');
    const pickers = [...root.querySelectorAll('[data-picker]')];
    const drawerMedia = window.matchMedia(DRAWER_QUERY);

    let request = null;
    let searchTimer = null;
    let searchEntryPushed = false;

    root.classList.add('is-enhanced');
    history.replaceState({ browse: true }, '', window.location.href);

    /* ---------- URL <-> form ---------- */

    function formUrl() {
        const params = new URLSearchParams();

        for (const [key, value] of new FormData(form)) {
            if (typeof value === 'string' && value.trim() !== '') {
                params.append(key, key === 'search' ? value.trim() : value);
            }
        }
        if (params.get('sort') === DEFAULT_SORT) {
            params.delete('sort');
        }

        const query = params.toString();

        return form.action + (query ? `?${query}` : '');
    }

    function selectedClassIds() {
        const checked = form.querySelector('input[type="radio"][name="characters[]"]:checked');
        const legacy = [...form.querySelectorAll('[data-legacy-class]')].map((input) => input.value);

        return checked && checked.value !== '' ? [checked.value] : legacy;
    }

    /** Mirror the server's canonical filter state into the form (after chip, pagination or history navigation). */
    function syncForm(state) {
        const classIds = (state.characters || []).map(String);

        form.querySelectorAll('[data-legacy-class], [data-legacy-class-note]').forEach((element) => element.remove());
        if (classIds.length > 1) {
            const options = form.querySelector('.browse-classes__options');
            classIds.forEach((id) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'characters[]';
                input.value = id;
                input.dataset.legacyClass = '';
                options.before(input);
            });
        }

        for (const element of form.elements) {
            if (!element.name || element.type === 'hidden') {
                continue;
            }

            const isList = element.name.endsWith('[]');
            const key = element.name.replace(/\[\]$/, '');
            const value = state[key];

            if (element.name === 'characters[]') {
                element.checked = classIds.length > 1 ? false : element.value === (classIds[0] ?? '');
            } else if (isList) {
                element.checked = (value || []).map(String).includes(element.value);
            } else if (element.type === 'radio') {
                element.checked = String(value ?? '') === element.value;
            } else if (element.type === 'checkbox') {
                element.checked = String(value ?? '') === element.value;
            } else if (element === sortSelect) {
                element.value = value || DEFAULT_SORT;
            } else if (element === searchInput) {
                element.value = value || '';
            }
        }

        applyScope();
        updatePickerCounts();
    }

    /* ---------- results ---------- */

    async function load(url, { history: historyMode = 'push', sync = false } = {}) {
        request?.abort();
        const controller = new AbortController();
        request = controller;

        root.classList.add('is-loading');
        body.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'text/html' },
                credentials: 'same-origin',
                cache: 'no-store',
                signal: controller.signal,
            });

            if (!response.ok) {
                throw new Error(`Browse request failed with ${response.status}`);
            }

            body.innerHTML = await response.text();

            if (historyMode === 'push') {
                history.pushState({ browse: true }, '', url);
            } else if (historyMode === 'replace') {
                history.replaceState({ browse: true }, '', url);
            }

            const fragment = body.querySelector('[data-browse-fragment]');
            if (fragment) {
                updateSummary(fragment);
                if (sync) {
                    syncForm(JSON.parse(fragment.dataset.state || '{}'));
                }
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                window.location.assign(url);
            }
        } finally {
            if (request === controller) {
                request = null;
                root.classList.remove('is-loading');
                body.setAttribute('aria-busy', 'false');
            }
        }
    }

    function updateSummary(fragment) {
        const filterCount = Number(fragment.dataset.filterCount || 0);

        root.querySelector('[data-browse-count]').textContent = fragment.dataset.summary;
        root.querySelectorAll('[data-browse-count-short]').forEach((element) => {
            element.textContent = fragment.dataset.total;
        });
        root.querySelectorAll('[data-browse-filter-count]').forEach((element) => {
            element.textContent = String(filterCount);
            element.hidden = filterCount === 0;
        });
        root.querySelectorAll('[data-browse-clear]').forEach((element) => {
            element.hidden = filterCount === 0;
        });
    }

    function update() {
        searchEntryPushed = false;
        load(formUrl());
    }

    /* ---------- pickers ---------- */

    function applyScope() {
        const classIds = selectedClassIds();

        pickers.forEach((picker) => {
            const requiresClass = picker.dataset.requiresClass === 'true';

            picker.querySelectorAll('.browse-picker__group').forEach((group) => {
                const inScope = requiresClass
                    ? classIds.includes(group.dataset.class)
                    : classIds.length === 0 || classIds.includes(group.dataset.class);

                group.querySelectorAll('.browse-option').forEach((option) => {
                    option.classList.toggle('is-out-of-scope', !inScope && !option.querySelector('input').checked);
                });
            });

            const prompt = picker.querySelector('[data-class-prompt]');
            if (prompt) {
                prompt.hidden = !(requiresClass && classIds.length === 0);
            }

            applySearch(picker);
        });
    }

    function applySearch(picker) {
        const input = picker.querySelector('[data-picker-search] input');
        const terms = input.value.trim().toLowerCase().split(/\s+/).filter(Boolean);
        let visibleOptions = 0;

        picker.querySelectorAll('.browse-picker__group').forEach((group) => {
            let visibleInGroup = 0;

            group.querySelectorAll('.browse-option').forEach((option) => {
                const matches = terms.every((term) => option.dataset.option.includes(term));
                option.classList.toggle('is-filtered-out', !matches);
                if (matches && !option.classList.contains('is-out-of-scope')) {
                    visibleInGroup++;
                }
            });

            group.classList.toggle('is-out-of-scope', visibleInGroup === 0);
            visibleOptions += visibleInGroup;
        });

        picker.querySelector('[data-picker-empty]').hidden = terms.length === 0 || visibleOptions > 0;
    }

    function updatePickerCounts() {
        pickers.forEach((picker) => {
            const count = picker.querySelectorAll('.browse-option input:checked').length;
            const badge = picker.querySelector('[data-picker-count]');
            badge.firstChild.textContent = String(count);
            badge.hidden = count === 0;
        });
    }

    /** Switching class drops gear that belongs to another class. */
    function onClassChange() {
        form.querySelectorAll('[data-legacy-class], [data-legacy-class-note]').forEach((element) => element.remove());

        const classIds = selectedClassIds();
        if (classIds.length > 0) {
            pickers.forEach((picker) => {
                picker.querySelectorAll('.browse-picker__group').forEach((group) => {
                    if (!classIds.includes(group.dataset.class)) {
                        group.querySelectorAll('input:checked').forEach((input) => {
                            input.checked = false;
                        });
                    }
                });
            });
        }

        applyScope();
        updatePickerCounts();
    }

    pickers.forEach((picker) => {
        const search = picker.querySelector('[data-picker-search]');
        const input = search.querySelector('input');

        search.hidden = false;
        input.addEventListener('input', () => applySearch(picker));
        input.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
            } else if (event.key === 'Escape' && input.value !== '') {
                event.stopPropagation();
                input.value = '';
                applySearch(picker);
            }
        });
    });

    /* ---------- drawer ---------- */

    function openDrawer() {
        drawer.classList.add('is-open');
        drawer.setAttribute('role', 'dialog');
        drawer.setAttribute('aria-modal', 'true');
        backdrop.hidden = false;
        results.inert = true;
        openButton.setAttribute('aria-expanded', 'true');
        document.documentElement.classList.add('browse-drawer-open');
        drawer.querySelector('.browse-filters__close').focus();
    }

    function closeDrawer({ restoreFocus = true } = {}) {
        if (!drawer.classList.contains('is-open')) {
            return;
        }
        drawer.classList.remove('is-open');
        drawer.removeAttribute('role');
        drawer.removeAttribute('aria-modal');
        backdrop.hidden = true;
        results.inert = false;
        openButton.setAttribute('aria-expanded', 'false');
        document.documentElement.classList.remove('browse-drawer-open');
        if (restoreFocus) {
            openButton.focus();
        }
    }

    openButton.addEventListener('click', openDrawer);
    root.querySelectorAll('[data-browse-close]').forEach((element) => element.addEventListener('click', () => closeDrawer()));
    root.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && drawer.classList.contains('is-open')) {
            closeDrawer();
        }
    });
    drawerMedia.addEventListener('change', (event) => {
        if (!event.matches) {
            closeDrawer({ restoreFocus: false });
        }
    });

    /* ---------- events ---------- */

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        clearTimeout(searchTimer);
        update();
    });

    form.addEventListener('change', (event) => {
        const target = event.target;
        if (!target.name || target === searchInput) {
            return;
        }
        if (target.name === 'characters[]') {
            onClassChange();
        } else {
            updatePickerCounts();
        }
        update();
    });

    sortSelect.addEventListener('change', update);

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            // One history entry per burst of typing.
            load(formUrl(), { history: searchEntryPushed ? 'replace' : 'push' });
            searchEntryPushed = true;
        }, SEARCH_DELAY);
    });
    searchInput.addEventListener('blur', () => {
        searchEntryPushed = false;
    });

    root.addEventListener('click', (event) => {
        const link = event.target.closest('a[data-browse-link]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }
        event.preventDefault();
        const isPagination = Boolean(link.closest('.browse-pagination'));
        load(link.href, { sync: true }).then(() => {
            if (isPagination && results.getBoundingClientRect().top < 0) {
                results.scrollIntoView({ block: 'start' });
            }
        });
    });

    window.addEventListener('popstate', (event) => {
        if (event.state?.browse) {
            load(window.location.href, { history: 'none', sync: true });
        }
    });

    applyScope();
}

const browseRoot = document.querySelector('[data-browse]');
if (browseRoot) {
    initBrowse(browseRoot);
}
