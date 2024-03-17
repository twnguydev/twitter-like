$(document).ready(function () {
    const localStorageKey = 'themePreference';
    const isNavigatorDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    let currentThemeValue = localStorage.getItem(localStorageKey) || (isNavigatorDarkMode ? 'dark' : 'light');

    $('html').attr('data-bs-theme', currentThemeValue);

    $('[data-bs-theme-value="' + currentThemeValue + '"]').addClass('active');

    $('[data-bs-theme-value]').on('click', function () {
        const themeValue = $(this).data('bs-theme-value');

        if (themeValue === 'auto') {
            const isNavigatorDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            currentThemeValue = isNavigatorDarkMode ? 'dark' : 'light';
        } else {
            currentThemeValue = themeValue;
        }

        localStorage.setItem(localStorageKey, currentThemeValue);

        $('html').attr('data-bs-theme', currentThemeValue);
        
        $('[data-bs-theme-value]').removeClass('active');
        $(this).addClass('active');

        $('[data-bs-theme-value]').attr('aria-pressed', 'false');
        $(this).attr('aria-pressed', 'true');
    });
});