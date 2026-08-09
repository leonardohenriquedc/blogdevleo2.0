/**
 * Lightweight client-side SEO helpers.
 *
 * The server already renders the correct <title>, canonical, Open Graph
 * and JSON-LD tags in the initial HTML (see the SeoMetaMiddleware and the
 * app.blade.php shell). Because this is a client-rendered SPA, these
 * helpers keep the browser's <title> and description in sync when the
 * user navigates between pages without a full page reload.
 */

const SITE_NAME = 'blogdevleo';

/**
 * Updates the document <title>.
 * @param {string} title
 */
export function setDocumentTitle(title) {
    document.title = title ? `${title} — ${SITE_NAME}` : SITE_NAME;
}

/**
 * Updates the meta[name="description"] tag.
 * @param {string} description
 */
export function setMetaDescription(description) {
    let meta = document.querySelector('meta[name="description"]');

    if (!meta) {
        meta = document.createElement('meta');
        meta.setAttribute('name', 'description');
        document.head.appendChild(meta);
    }

    meta.setAttribute('content', description || '');
}
