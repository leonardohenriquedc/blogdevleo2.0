const MONTHS_PT = [
    'JAN',
    'FEV',
    'MAR',
    'ABR',
    'MAI',
    'JUN',
    'JUL',
    'AGO',
    'SET',
    'OUT',
    'NOV',
    'DEZ',
];

/**
 * Formats an ISO date (YYYY-MM-DD) as "DD MMM YYYY" with the month in PT-BR,
 * e.g. "2026-08-08" -> "08 AGO 2026". Falls back to the raw value when it
 * cannot be parsed.
 * @param {string|Date|null|undefined} date
 * @returns {string}
 */
export function formatDate(date) {
    if (!date) {
        return '';
    }

    const value = new Date(date);

    if (Number.isNaN(value.getTime())) {
        return String(date);
    }

    const day = String(value.getUTCDate()).padStart(2, '0');
    const month = MONTHS_PT[value.getUTCMonth()];
    const year = value.getUTCFullYear();

    return `${day} ${month} ${year}`;
}

/**
 * Derives a friendly display title for an article. Prefers an explicit
 * `title` field, otherwise turns the `name`/slug into a readable sentence.
 * @param {{name?: string, title?: string}} article
 * @returns {string}
 */
export function articleTitle(article) {
    if (article?.title && String(article.title).trim().length > 0) {
        return String(article.title).trim();
    }

    if (!article?.name) {
        return '';
    }

    const words = String(article.name)
        .trim()
        .split(/[-_\s]+/)
        .filter(Boolean);

    return words
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

/**
 * Converts a display name (spaces) back into a URL slug (hyphens).
 * @param {string} name
 * @returns {string}
 */
export function toSlug(name) {
    return String(name).trim().toLowerCase().replace(/\s+/g, '-');
}
