/**
 * Tribute.js @mention autocomplete for Bible references in the sermon editor.
 * Attaches to .ql-editor (Quill) or [data-mention-editor="true"] or textarea[name="full_content"].
 * Uses GET /api/bible/autocomplete?q=... and optionally book_id= for chapter/verse.
 */
import Tribute from 'tributejs';
import 'tributejs/dist/tribute.css';

const AUTOCOMPLETE_URL = '/api/bible/autocomplete';

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
        const parts = q.split(/\s+/);
        const bookPart = parts[0];
        const restQ = parts.slice(1).join(' ');
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
      const ref = String(item.original?.reference || item.original?.name || item.string || '');
      return `<span class="font-medium">${ref.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</span>`;
    },
  };
}

export function attachBibleMentions(element) {
  if (!element || element.getAttribute('data-tribute') === 'true') return;
  const tribute = new Tribute({
    collection: [bibleMentionCollection()],
    positionMenu: true,
    menuContainer: document.body,
  });
  tribute.attach(element);
}

export function initBibleMentionAutocomplete() {
  const editor =
    document.querySelector('.ql-editor') ||
    document.querySelector('[data-mention-editor="true"]') ||
    document.querySelector('textarea[name="full_content"]');
  if (editor) {
    attachBibleMentions(editor);
  }
}
