/* Project selection is held by the server session. These helpers only improve form interaction. */
(() => {
  'use strict';
  const script = document.currentScript;
  const project = script?.dataset.project;
  if (project) {
    document.querySelectorAll('select[name$="_project"], select[name="project"]').forEach(select => {
      if (!select.closest('form[data-report-filters]') && select.querySelector(`option[value="${project}"]`)) {
        const changed = select.value !== project;
        select.value = project;
        if (changed) select.dispatchEvent(new Event('change', {bubbles: true}));
      }
    });
  }

  const filters = document.querySelector('[data-report-filters]');
  if (filters) {
    const update = () => {
      const material = filters.elements.category.value === 'materials';
      const custom = filters.elements.period.value === 'custom';
      filters.querySelectorAll('[data-report-material]').forEach(e => { e.hidden = !material; });
      filters.querySelectorAll('[data-report-labour]').forEach(e => { e.hidden = material; });
      filters.querySelectorAll('[data-report-custom]').forEach(e => { e.hidden = !custom; });
      filters.elements.start.required = custom;
      filters.elements.end.required = custom;
    };
    filters.addEventListener('change', update);
    update();
  }

  const form = document.querySelector('[data-attendance-form]');
  if (!form) return;
  const body = form.querySelector('[data-attendance-rows]');
  const rowTemplate = body.querySelector('[data-attendance-row]').cloneNode(true);
  let nextIndex = Math.max(...[...form.querySelectorAll('[name^="workers[CATEGORY]"]')]
    .map(e => Number(e.name.match(/\[(\d+)\]$/)?.[1] || 0))) + 1;
  const calculate = () => {
    let total = 0;
    body.querySelectorAll('[data-attendance-row]').forEach(row => {
      const value = field => Math.max(0, Number(row.querySelector(`[data-wage="${field}"]`)?.value) || 0);
      const people = value('MALE') + value('FEMALE');
      const base = people * Math.round(value('WAGE') * 100);
      const overtime = Math.round(people * value('OVERTIME_HOURS') * Math.round(value('OVERTIME_RATE') * 100));
      const cents = base + overtime;
      row.querySelector('[data-row-total]').value = (cents / 100).toFixed(2);
      total += cents;
    });
    form.querySelector('[data-attendance-total]').value = (total / 100).toFixed(2);
    const minutes = text => { const [h, m] = text.split(':').map(Number); return h * 60 + m; };
    const start = minutes(form.elements.ltracker_in.value);
    const end = minutes(form.elements.ltracker_out.value);
    form.elements.ltracker_thours.value = Number.isFinite(start + end) ? (((end - start + 1440) % 1440) / 60).toFixed(2) : '';
  };
  form.addEventListener('input', calculate);
  form.addEventListener('click', event => {
    const remove = event.target.closest('[data-remove-attendance]');
    if (remove && body.children.length > 1) { remove.closest('[data-attendance-row]').remove(); calculate(); }
    if (event.target.closest('[data-add-attendance]')) {
      const row = rowTemplate.cloneNode(true);
      row.querySelectorAll('[name]').forEach(input => {
        input.name = input.name.replace(/\[\d+\]$/, `[${nextIndex}]`);
        if (input.tagName === 'SELECT') input.selectedIndex = 0;
        else input.value = '0';
      });
      nextIndex += 1;
      body.append(row);
      calculate();
    }
  });
  calculate();
})();
