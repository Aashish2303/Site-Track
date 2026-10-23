/* Navigation search only indexes links already allowed by the server-rendered sidebar. */
(() => {
  'use strict';
  const dialog = document.getElementById('st-command-menu');
  const trigger = document.getElementById('st-command-toggle');
  if (!dialog || !trigger) return;
  const input = document.getElementById('st-command-input');
  const results = document.getElementById('st-command-results');
  const seen = new Set();
  const pages = [...document.querySelectorAll('#sidebar-menu a[href]')].filter(link => {
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || seen.has(link.href)) return false;
    seen.add(link.href);
    return true;
  }).map(link => ({ href: link.href, title: link.textContent.trim().replace(/\s+/g, ' '), icon: link.querySelector('i')?.className || 'ti ti-arrow-up-right' }));
  function render() {
    results.replaceChildren();
    const query = input.value.trim().toLocaleLowerCase();
    const matches = pages.filter(page => page.title.toLocaleLowerCase().includes(query));
    if (!matches.length) {
      const empty = document.createElement('p');
      empty.className = 'st-empty';
      empty.textContent = 'No matching pages. Try another name.';
      results.append(empty);
      return;
    }
    matches.forEach(page => {
      const link = document.createElement('a');
      link.href = page.href;
      const icon = document.createElement('i');
      icon.className = page.icon;
      icon.setAttribute('aria-hidden', 'true');
      const text = document.createElement('span');
      text.textContent = page.title;
      const arrow = document.createElement('i');
      arrow.className = 'ti ti-arrow-up-right';
      arrow.setAttribute('aria-hidden', 'true');
      link.append(icon, text, arrow);
      results.append(link);
    });
  }
  function open() {
    if (dialog.open) return;
    input.value = '';
    render();
    dialog.showModal();
    input.focus();
  }
  trigger.addEventListener('click', open);
  input.addEventListener('input', render);
  dialog.querySelector('[data-st-close]').addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
  dialog.addEventListener('close', () => trigger.focus());
  document.addEventListener('keydown', event => {
    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
      event.preventDefault();
      if (dialog.open) dialog.close(); else open();
    }
    if (!dialog.open || !['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) return;
    const links = [...results.querySelectorAll('a')];
    if (!links.length) return;
    const index = links.indexOf(document.activeElement);
    if (event.key === 'Enter' && document.activeElement === input) {
      event.preventDefault();
      links[0].click();
    } else if (event.key !== 'Enter') {
      event.preventDefault();
      const next = event.key === 'ArrowDown' ? (index + 1) % links.length : (index <= 0 ? links.length - 1 : index - 1);
      links[next].focus();
    }
  });
  const mobile = document.getElementById('mobile_btn');
  const wrapper = document.querySelector('.main-wrapper');
  if (mobile && wrapper) new MutationObserver(() => {
    mobile.setAttribute('aria-expanded', String(wrapper.classList.contains('slide-nav')));
  }).observe(wrapper, { attributes: true, attributeFilter: ['class'] });
})();
