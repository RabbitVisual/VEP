/**
 * Tribute.js @mention autocomplete for Bible references in the sermon editor.
 * Attaches to .ql-editor (Quill) or [data-mention-editor="true"] (textarea fallback).
 * Uses GET /api/bible/autocomplete?q=... and optionally book_id= for chapter/verse.
 */
import Tribute from 'tributejs';

const API_BASE = (typeof window !== 'undefined' && window.location?.origin) ? '' : '/';
const AUTOCOMPLETE_URL = `${API_BASE}/api/bible/autocomplete`;

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
}

/**
 * Fetch book id by name (first match from autocomplete).
 */
async function resolveBookId(bookName) {
  const q = String(bookName).trim();
  if (!q) return null;
  try {
    const res = await fetch(`${AUTOCOMPLETE_URL}?q=${encodeURIComponent(q)}`, {
      headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    });
    if (!res.ok) return null;
    const data = await res.json();
    const first = Array.isArray(data) ? data[0] : (data && data[0]);
    return first && first.id != null ? first.id : null;
  } catch {
    return null;
  }
}

/**
 * Build Tribute collection for Bible @mentions: async values from api/bible/autocomplete.
 */
function bibleMentionCollection() {
  return {
    trigger: '@',
    requireLeadingSpace: true,
    allowSpaces: true,
    menuShowMinLength: 1,
    menuItemLimit: 10,
    lookup: 'reference',
    fillAttr: 'reference',
    values: async (mentionText, callback) => {
      const q = String(mentionText || '').trim();
      if (!q) {
        callback([]);
        return;
      }
      const hasSpace = /\s/.test(q);
      let items = [];
      if (hasSpace) {
        const [bookPart, ...rest] = q.split(/\s+/);
        const restQ = rest.join(' ');
        const bookId = await resolveBookId(bookPart);
        if (bookId != null && restQ) {
          try {
            const res = await fetch(
              `${AUTOCOMPLETE_URL}?q=${encodeURIComponent(restQ)}&book_id=${bookId}`,
              { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }
            );
            if (res.ok) {
              const data = await res.json();
              items = Array.isArray(data) ? data : (data && data.data) || [];
            }
          } catch {
            items = [];
          }
        }
      } else {
        try {
          const res = await fetch(`${AUTOCOMPLETE_URL}?q=${encodeURIComponent(q)}`, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          });
          if (res.ok) {
            const data = await res.json();
            const raw = Array.isArray(data) ? data : (data && data.data) || [];
            items = raw.map((b) => ({
              id: b.id,
              name: b.name,
              reference: b.reference != null ? b.reference : (b.name || b.abbreviation),
            }));
          }
        } catch {
          items = [];
        }
      }
      callback(items);
    },
    selectTemplate: (item) => {
      if (!item || !item.original) return '@';
      const ref = item.original.reference || item.original.name || '';
      return `@${ref}`;
    },
    menuItemTemplate: (item) => {
      const ref = item.original?.reference || item.original?.name || item.string || '';
      return `<span class="font-medium">${escapeHtml(ref)}</span>`;
    },
  };
}

function escapeHtml(s) {
  const div = document.createElement('div');
  div.textContent = s;
  return div.innerHTML;
}

let tributeInstance = null;

/**
 * Initialize Tribute on the given element (contenteditable or textarea).
 */
export function attachBibleMentions(element) {
  if (!element || element.getAttribute('data-tribute') === 'true') return;
  tributeInstance = new Tribute({
    collection: [bibleMentionCollection()],
    positionMenu: true,
    menuContainer: document.body,
  });
  tributeInstance.attach(element);
}

/**
 * Find editor element and attach Tribute. Call after DOM ready (e.g. on sermon edit/create).
 */
export function initBibleMentionAutocomplete() {
  const editor =
    document.querySelector('.ql-editor') ||
    document.querySelector('[data-mention-editor="true"]') ||
    document.querySelector('textarea[name="full_content"]');
  if (editor) {
    attachBibleMentions(editor);
  }
}
