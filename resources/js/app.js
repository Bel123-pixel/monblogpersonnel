import './bootstrap';

/* ═══════════════════════════════════════════════
   BellevieShop BLOG — app.js
═══════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {

  // ── Cursor personnalisé ─────────────────────
  const dot  = document.getElementById('cursorDot');
  const ring = document.getElementById('cursorRing');
  if (dot && ring && window.innerWidth > 768) {
    document.addEventListener('mousemove', e => {
      dot.style.left  = e.clientX + 'px';
      dot.style.top   = e.clientY + 'px';
      ring.style.left = e.clientX + 'px';
      ring.style.top  = e.clientY + 'px';
    });
    document.querySelectorAll('a, button, .post-card').forEach(el => {
      el.addEventListener('mouseenter', () => {
        ring.style.width = '44px'; ring.style.height = '44px';
        ring.style.opacity = '.3'; ring.style.borderColor = 'var(--blue)';
      });
      el.addEventListener('mouseleave', () => {
        ring.style.width = '28px'; ring.style.height = '28px';
        ring.style.opacity = '.5';
      });
    });
  }

  // ── Navbar scroll ───────────────────────────
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar?.classList.toggle('scrolled', scrollY > 12);
  }, { passive: true });

  // ── Auto-dismiss toasts ─────────────────────
  document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .4s, transform .4s';
      el.style.opacity = '0';
      el.style.transform = 'translateX(20px)';
      setTimeout(() => el.remove(), 400);
    }, 4500);
  });

  // ── Confirm delete ──────────────────────────
  document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', e => {
      if (!confirm(btn.dataset.confirm || 'Confirmer ?')) e.preventDefault();
    });
  });

  // ── Reply toggle ────────────────────────────
  document.querySelectorAll('.reply-btn[data-cid]').forEach(btn => {
    btn.addEventListener('click', () => {
      const form = document.getElementById('rf-' + btn.dataset.cid);
      form?.classList.toggle('open');
      if (form?.classList.contains('open')) form.querySelector('textarea')?.focus();
    });
  });

  // ── Inline edit toggle ──────────────────────
  document.querySelectorAll('.edit-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const id   = btn.dataset.id;
      const type = btn.dataset.type || 'comment';
      const disp = document.getElementById(`${type}-disp-${id}`);
      const form = document.getElementById(`${type}-edit-${id}`);
      if (disp && form) {
        const show = form.style.display === 'none' || !form.style.display;
        disp.style.display = show ? 'none' : '';
        form.style.display = show ? '' : 'none';
        if (show) form.querySelector('textarea')?.focus();
      }
    });
  });

  // ── Staggered card animations ───────────────
  document.querySelectorAll('.post-card').forEach((card, i) => {
    card.style.animationDelay = `${i * 55}ms`;
  });
  document.querySelectorAll('.stat-card').forEach((card, i) => {
    card.style.animationDelay = `${i * 60}ms`;
  });

  // ── Mention autocomplete ────────────────────
  initMentions();

  // ── Close dropdowns outside ─────────────────
  document.addEventListener('click', e => {
    if (!e.target.closest('#notifWrap'))    closeNotif();
    if (!e.target.closest('#userMenuWrap')) document.getElementById('userMenu')?.classList.remove('open');
  });

  // ── Char counter ────────────────────────────
  document.querySelectorAll('[data-max]').forEach(el => {
    const max = +el.dataset.max;
    const counter = Object.assign(document.createElement('small'), {
      className: 'form-hint', textContent: `0 / ${max}`
    });
    el.parentNode.appendChild(counter);
    el.addEventListener('input', () => {
      counter.textContent = `${el.value.length} / ${max}`;
      counter.style.color = el.value.length > max * .9 ? 'var(--danger)' : '';
    });
  });

  // ── Reply buttons avec data-author/data-body ─
  document.querySelectorAll('.reply-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      if (this.dataset.author) openReply(this.dataset.author, this.dataset.body);
    });
  });
});

// ── Navigation mobile ────────────────────────
window.toggleMobile = function() {
  document.getElementById('navMenu')?.classList.toggle('open');
  document.getElementById('hamburger')?.classList.toggle('open');
};

// ── Notification dropdown ────────────────────
window.toggleNotif = function() {
  const panel = document.getElementById('notifPanel');
  const isOpen = panel?.classList.toggle('open');
  if (isOpen) fetchNotifList();
};

window.closeNotif = function() {
  document.getElementById('notifPanel')?.classList.remove('open');
};

window.loadNotifCount = function() {
  fetch('/notifications/count')
    .then(r => r.json())
    .then(({ count }) => {
      const badge = document.getElementById('notifCount');
      if (!badge) return;
      badge.textContent = count > 99 ? '99+' : count;
      badge.style.display = count > 0 ? 'flex' : 'none';
    }).catch(() => {});
};

function fetchNotifList() {
  const body = document.getElementById('notifBody');
  if (!body) return;
  body.innerHTML = '<div class="notif-spinner"><i class="fas fa-circle-notch fa-spin"></i></div>';

  fetch('/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.text())
    .then(html => {
      const doc   = new DOMParser().parseFromString(html, 'text/html');
      const items = [...doc.querySelectorAll('.notif-item-page')].slice(0, 5);
      if (!items.length) {
        body.innerHTML = '<div class="notif-spinner" style="padding:1.5rem">Aucune notification</div>';
        return;
      }
      body.innerHTML = '';
      items.forEach(item => {
        const msg    = item.querySelector('.notif-msg')?.textContent?.trim() || '';
        const time   = item.querySelector('.notif-time')?.textContent?.trim() || '';
        const img    = item.querySelector('img')?.src || '';
        const unread = item.classList.contains('unread');
        const id     = item.dataset.nid;
        const a      = document.createElement('a');
        a.href      = id ? `/notifications/${id}/read` : '/notifications';
        a.className = 'np-item' + (unread ? ' unread' : '');
        a.innerHTML = `
          <img src="${img}" alt="" onerror="this.src='https://ui-avatars.com/api/?name=U&background=2563eb&color=fff'">
          <div>
            <div class="np-item-msg">${msg}</div>
            <div class="np-item-time">${time}</div>
          </div>`;
        body.appendChild(a);
      });
    }).catch(() => {
      body.innerHTML = '<div class="notif-spinner">Erreur de chargement</div>';
    });
}

// ── User menu toggle ─────────────────────────
window.toggleUserMenu = function() {
  document.getElementById('userMenu')?.classList.toggle('open');
};

// ── Image preview ────────────────────────────
window.previewImage = function(input, targetId) {
  const target = document.getElementById(targetId);
  if (!target || !input.files?.[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    target.src = e.target.result;
    target.style.display = 'block';
  };
  reader.readAsDataURL(input.files[0]);
};

// ── Reply functions ──────────────────────────
window.openReply = function(authorMention, commentBody) {
  const area    = document.getElementById('comment-area');
  const preview = document.getElementById('reply-preview');
  const previewAuthor = document.getElementById('reply-preview-author');
  const previewBody   = document.getElementById('reply-preview-body');
  if (!area || !preview) return;
  previewAuthor.textContent = authorMention;
  previewBody.textContent   = commentBody || '';
  preview.style.display     = 'block';
  area.value = authorMention + ' ';
  area.focus();
  area.setSelectionRange(area.value.length, area.value.length);
  area.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

window.cancelReply = function() {
  const preview = document.getElementById('reply-preview');
  if (preview) preview.style.display = 'none';
  const area = document.getElementById('comment-area');
  if (area && area.value.startsWith('@')) area.value = '';
};

// ── Mention autocomplete ─────────────────────
function initMentions() {
  document.querySelectorAll('textarea[data-mentions]').forEach(ta => {
    const users = window.blogUsers || [];
    const popup = document.createElement('div');
    popup.className = 'mention-popup';
    ta.parentElement.style.position = 'relative';
    ta.parentElement.appendChild(popup);

    ta.addEventListener('input', () => {
      const before = ta.value.slice(0, ta.selectionStart);
      const match  = before.match(/@([a-zA-Z0-9_]*)$/);
      if (!match || match[1].length < 1) { popup.classList.remove('open'); return; }

      const q       = match[1].toLowerCase();
      const results = users.filter(u => u.username.toLowerCase().includes(q)).slice(0, 5);
      if (!results.length) { popup.classList.remove('open'); return; }

      popup.innerHTML = results.map(u => `
        <div class="mention-option" data-u="${u.username}">
          <img src="${u.avatar}" alt=""
            onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(u.name)}&background=2563eb&color=fff'">
          <div>
            <div style="font-weight:600;font-size:.84rem">${u.name}</div>
            <div style="font-size:.74rem;color:var(--muted)">@${u.username}</div>
          </div>
        </div>`).join('');
      popup.classList.add('open');

      popup.querySelectorAll('.mention-option').forEach(opt => {
        opt.addEventListener('mousedown', e => {
          e.preventDefault();
          const cursor  = ta.selectionStart;
          const before2 = ta.value.slice(0, cursor).replace(/@([a-zA-Z0-9_]*)$/, '@' + opt.dataset.u + ' ');
          ta.value = before2 + ta.value.slice(cursor);
          popup.classList.remove('open');
          ta.focus();
        });
      });
    });

    document.addEventListener('click', e => {
      if (e.target !== ta && !popup.contains(e.target)) popup.classList.remove('open');
    });
  });
}
