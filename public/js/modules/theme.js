const STORAGE_KEY = 'midair-theme';
const DARK = 'dark';
const LIGHT = 'light';

export const themeManager = {
    init() {
        const saved = localStorage.getItem(STORAGE_KEY);
        const preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? DARK : LIGHT;
        this.applyTheme(saved ?? preferred);

        document.getElementById('theme-toggle')?.addEventListener('click', () => this.toggle());

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem(STORAGE_KEY)) {
                this.applyTheme(e.matches ? DARK : LIGHT);
            }
        });
    },
    toggle() {
        const current = document.documentElement.getAttribute('data-theme');
        const next = current === DARK ? LIGHT : DARK;
        localStorage.setItem(STORAGE_KEY, next);
        this.applyTheme(next);
    },
    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
    }
};
