/**
 * Lightweight API client for the blog backend endpoints.
 *
 * - GET  /get          -> { blogs: [{ name, date }] }
 * - GET  /get/{name}   -> { content: "<html>" }  |  404
 */
const BASE_URL = '';

async function request(url) {
    const response = await fetch(`${BASE_URL}${url}`, {
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (response.status === 404) {
        const error = new Error('Recurso não encontrado');
        error.status = 404;

        throw error;
    }

    if (!response.ok) {
        const error = new Error(`Falha na requisição (${response.status})`);
        error.status = response.status;

        throw error;
    }

    return response.json();
}

/**
 * Fetches the list of blog articles.
 * @returns {Promise<Array<{name: string, date?: string}>>}
 */
export async function fetchArticles() {
    const data = await request('/get');

    return Array.isArray(data.blogs) ? data.blogs : [];
}

/**
 * Fetches a single article (Markdown rendered to HTML by the backend).
 * @param {string} name slug of the article.
 * @returns {Promise<{content: string}>}
 */
export async function fetchArticle(name) {
    const slug = String(name).trim().toLowerCase().replace(/\s+/g, '-');

    return request(`/get/${encodeURIComponent(slug)}`);
}
