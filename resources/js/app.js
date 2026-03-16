// Vertex Escola de Pastores – app entry (tudo local, sem CDN)
import '../css/app.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
  Alpine.data('communityPostCard', (props) => ({
    postId: props.postId,
    reactions: {
      like: props.reactions?.like ?? 0,
      amen: props.reactions?.amen ?? 0,
      praying: props.reactions?.praying ?? 0,
    },
    userReactions: [],
    commentsCount: props.commentsCount ?? 0,
    showComments: false,
    comments: [],
    isSubmittingComment: false,

    hasReaction(type) {
      return this.userReactions.includes(type);
    },

    async toggleReaction(type) {
      if (!this.postId) return;
      try {
        const res = await fetch(`/painel/community/posts/${this.postId}/reactions`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          },
          body: JSON.stringify({ type }),
        });
        if (!res.ok) return;
        const data = await res.json();
        if (data?.counts) {
          this.reactions.like = data.counts.like ?? this.reactions.like;
          this.reactions.amen = data.counts.amen ?? this.reactions.amen;
          this.reactions.praying = data.counts.praying ?? this.reactions.praying;
        }
        if (Array.isArray(data?.user_reactions)) {
          this.userReactions = data.user_reactions;
        }
      } catch {
        // fail silently for now
      }
    },

    toggleComments() {
      this.showComments = !this.showComments;
      if (this.showComments && this.comments.length === 0) {
        // Podemos carregar comentários completos via endpoint dedicado em uma etapa futura
      }
      if (this.showComments && this.$refs.commentInput) {
        this.$nextTick(() => {
          try {
            this.$refs.commentInput.focus();
          } catch {}
        });
      }
    },

    async submitComment() {
      if (this.isSubmittingComment || !this.postId || !this.$refs.commentInput) return;
      const content = this.$refs.commentInput.value.trim();
      if (!content) return;

      this.isSubmittingComment = true;
      try {
        const res = await fetch(`/painel/community/posts/${this.postId}/comments`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          },
          body: JSON.stringify({ content }),
        });
        if (!res.ok) {
          this.isSubmittingComment = false;
          return;
        }
        const data = await res.json();
        if (data?.comment) {
          this.comments.unshift(data.comment);
          this.commentsCount += 1;
          this.$refs.commentInput.value = '';

          if (window.initBibleMentionAutocomplete) {
            window.initBibleMentionAutocomplete();
          } else if (window.Alpine && window.Alpine.initTree) {
            this.$nextTick(() => {
              window.Alpine.initTree(this.$root);
            });
          }
        }
      } catch {
        // ignore
      } finally {
        this.isSubmittingComment = false;
      }
    },
  }));

  Alpine.data('communityCommentNode', (props) => ({
    commentId: props.commentId,
    reactions: {
      like: props.reactions?.like ?? 0,
      amen: props.reactions?.amen ?? 0,
      praying: props.reactions?.praying ?? 0,
    },
    userReactions: [],
    showReply: false,
    isSubmittingReply: false,

    hasReaction(type) {
      return this.userReactions.includes(type);
    },

    async toggleReaction(type) {
      if (!this.commentId) return;
      try {
        const res = await fetch(`/painel/community/comments/${this.commentId}/reactions`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          },
          body: JSON.stringify({ type }),
        });
        if (!res.ok) return;
        const data = await res.json();
        if (data?.counts) {
          this.reactions.like = data.counts.like ?? this.reactions.like;
          this.reactions.amen = data.counts.amen ?? this.reactions.amen;
          this.reactions.praying = data.counts.praying ?? this.reactions.praying;
        }
        if (Array.isArray(data?.user_reactions)) {
          this.userReactions = data.user_reactions;
        }
      } catch {
        // ignore
      }
    },

    toggleReply() {
      this.showReply = !this.showReply;
      if (this.showReply && this.$refs.replyInput) {
        this.$nextTick(() => {
          try {
            this.$refs.replyInput.focus();
          } catch {}
        });
      }
    },

    async submitReply(postId) {
      if (this.isSubmittingReply || !this.$refs.replyInput || !postId) return;
      const content = this.$refs.replyInput.value.trim();
      if (!content) return;

      this.isSubmittingReply = true;
      try {
        const res = await fetch(`/painel/community/posts/${postId}/comments`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          },
          body: JSON.stringify({ content, parent_id: this.commentId }),
        });
        if (!res.ok) {
          this.isSubmittingReply = false;
          return;
        }
        const data = await res.json();
        if (data?.comment) {
          this.$refs.replyInput.value = '';
          this.showReply = false;

          if (window.Alpine && window.Alpine.initTree) {
            this.$nextTick(() => {
              window.Alpine.initTree(this.$root);
            });
          }
        }
      } catch {
        // ignore
      } finally {
        this.isSubmittingReply = false;
      }
    },
  }));
});

Alpine.start();

// Tema claro/escuro: um único listener, troca rápida sem travar
(function () {
  const THEME_KEY = 'theme';
  const ROOT = document.documentElement;

  function applyTheme(isDark) {
    ROOT.classList.toggle('dark', isDark);
    ROOT.setAttribute('data-theme', isDark ? 'dark' : 'light');
  }

  function getPreferredDark() {
    const stored = localStorage.getItem(THEME_KEY);
    if (stored === 'dark' || stored === 'light') return stored === 'dark';
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  // Aplicar tema na carga (já feito no <head>; reforço para ícones)
  applyTheme(getPreferredDark());

  document.body.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-theme-toggle]');
    if (!btn) return;
    e.preventDefault();
    const isDark = !ROOT.classList.contains('dark');
    applyTheme(isDark);
    localStorage.setItem(THEME_KEY, isDark ? 'dark' : 'light');
  });
})();

// Bible @mentions: init when an editor with data-mention-editor="true" is present (e.g. Ministry materials)
(function () {
  function init() {
    if (!document.querySelector('[data-mention-editor="true"]')) return;
    import('./components/mention-autocomplete.js').then((m) => {
      if (typeof m.initBibleMentionAutocomplete === 'function') m.initBibleMentionAutocomplete();
    }).catch(() => {});
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
