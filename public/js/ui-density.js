const STORAGE_KEY = 'ui-density';
const DEFAULT_DENSITY = 'comfortable';
const COMPACT_DENSITY = 'compact';

const root = document.documentElement;

function readStoredDensity() {
    try {
        const stored = window.localStorage.getItem(STORAGE_KEY);
        return stored === COMPACT_DENSITY ? COMPACT_DENSITY : DEFAULT_DENSITY;
    } catch (error) {
        return DEFAULT_DENSITY;
    }
}

function writeStoredDensity(value) {
    try {
        window.localStorage.setItem(STORAGE_KEY, value);
    } catch (error) {
        // Ignore storage failures and keep the current session-only density.
    }
}

function applyDensity(value) {
    const density = value === COMPACT_DENSITY ? COMPACT_DENSITY : DEFAULT_DENSITY;
    root.dataset.uiDensity = density;

    const label = density === COMPACT_DENSITY ? 'Compact' : 'Comfortable';
    document.querySelectorAll('[data-ui-density-state]').forEach((node) => {
        node.textContent = label;
    });
    document.querySelectorAll('[data-ui-density-toggle]').forEach((button) => {
        button.setAttribute('aria-pressed', density === COMPACT_DENSITY ? 'true' : 'false');
    });
}

function toggleDensity() {
    const nextDensity = root.dataset.uiDensity === COMPACT_DENSITY ? DEFAULT_DENSITY : COMPACT_DENSITY;
    applyDensity(nextDensity);
    writeStoredDensity(nextDensity);
}

function ensureToggleButton() {
    if (document.querySelector('[data-ui-density-toggle]')) {
        return;
    }

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'ui-density-toggle';
    button.setAttribute('aria-pressed', root.dataset.uiDensity === COMPACT_DENSITY ? 'true' : 'false');
    button.setAttribute('aria-label', 'Toggle compact mode');
    button.setAttribute('data-ui-density-toggle', '');
    button.innerHTML = '<span class="ui-density-toggle__label">Compact mode</span><span class="ui-density-toggle__state" data-ui-density-state>Comfortable</span>';
    button.addEventListener('click', toggleDensity);

    document.body.appendChild(button);
}

applyDensity(readStoredDensity());

document.addEventListener('DOMContentLoaded', () => {
    ensureToggleButton();
});
