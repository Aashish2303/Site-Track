/* Shared presentation helpers. Actions are reused only from permission-checked server markup. */
(() => {
  'use strict';
  if (!document.body.classList.contains('st-app')) return;
  const $ = window.jQuery;
  const descriptions = {
    daily_works: ['ti-calendar', 'Record work for this project to start your daily site diary.'],
    daily_progress: ['ti-chart-bar', 'Submit quantities and site photos for engineer verification.'],
    labour_trackers: ['ti-users-group', 'Record crew attendance to see wages and overtime here.'],
    users: ['ti-user-plus', 'Add team members and manage their access to your sites.'],
    indents: ['ti-clipboard', 'Create a material request to track quotations and approvals.'],
    issues: ['ti-alert-triangle', 'Log a site issue to track its priority and resolution.'],
    materials: ['ti-package', 'Add materials to your catalogue before receiving or assigning them.'],
    orders: ['ti-truck-delivery', 'Create a purchase order to track deliveries and payments.'],
    vendors: ['ti-briefcase', 'Add suppliers so their orders and expenses stay organised.'],
    subcontractors: ['ti-users', 'Add subcontractors to connect site work, attendance and costs.'],
    'site-materials': ['ti-package', 'Receive materials into this site and record usage as work progresses.'],
    'labour-payments': ['ti-wallet', 'Attendance, advances and payments for this period will appear here.'],
    reports: ['ti-report-analytics', 'Try another project or period, or record the first entries for this site.'],
    workspace: ['ti-list-check', 'Add BOQ items to start tracking reported and approved quantities.'],
  };
  const page = location.pathname.split('/').filter(Boolean).at(-1);
  const [icon, description] = descriptions[page] || ['ti-inbox', 'Your entries will appear here once they are recorded.'];

  function normalizeAddButtons() {
    document.querySelectorAll('.content > .d-flex a.btn, .content > .st-page-intro a.btn').forEach(button => {
      if (button.classList.contains('st-add-button')) return;
      const label = button.textContent.trim();
      if (!/^(Add|New)\b/i.test(label) && !/\/add_[a-z_]+/.test(button.getAttribute('href') || '')) return;
      button.classList.remove('btn-success', 'btn-warning', 'btn-secondary', 'btn-white', 'btn-sm');
      button.classList.add('btn-primary', 'st-add-button');
      button.querySelectorAll('i.ti-plus, i.ti-square-rounded-plus').forEach(i => i.remove());
      const plus = document.createElement('span');
      plus.className = 'st-add-mark'; plus.setAttribute('aria-hidden', 'true'); plus.textContent = '+';
      button.prepend(plus);
    });
  }

  function makeEmpty(message, filtered = false, allowAdd = true) {
    const box = document.createElement('div'); box.className = 'st-empty-state';
    const symbol = document.createElement('i'); symbol.className = 'ti ' + (filtered ? 'ti-search' : icon); symbol.setAttribute('aria-hidden', 'true');
    const heading = document.createElement('h5'); heading.textContent = filtered ? 'No matching entries' : 'No entries yet';
    const detail = document.createElement('p'); detail.textContent = message;
    box.append(symbol, heading, detail);
    const original = document.querySelector('.content .st-add-button');
    if (original && allowAdd && !filtered) {
      const add = document.createElement('button'); add.type = 'button'; add.className = 'btn btn-primary st-add-button';
      add.textContent = original.textContent.trim().replace(/^\+\s*/, '+ ');
      add.addEventListener('click', () => original.click()); box.append(add);
    }
    return box;
  }

  function decorateCell(table, cell, filtered = false) {
    if (!cell.querySelector('.st-empty-state')) {
      cell.replaceChildren(makeEmpty(filtered ? 'Change the search or date filters to see more entries.' : description, filtered));
    }
    table.classList.add('st-table-empty');
  }

  if ($?.fn.dataTable) {
    $.extend(true, $.fn.dataTable.defaults, {language: {emptyTable: 'No entries yet', zeroRecords: 'No matching entries'}});
    $(document).on('draw.dt', (event, settings) => {
      const table = settings.nTable;
      if (settings.oFeatures.bServerSide && !settings.json) return;
      const cell = table.querySelector('td.dataTables_empty');
      if (cell && (!settings.jqXHR || settings.jqXHR.status < 400)) {
        const filtered = new $.fn.dataTable.Api(settings).page.info().recordsTotal > 0;
        decorateCell(table, cell, filtered);
      } else if (!cell) {
        table.classList.remove('st-table-empty');
      }
    });
  }

  function staticStates() {
    normalizeAddButtons();
    document.querySelectorAll('.content table.table').forEach(table => {
      if ($?.fn.dataTable?.isDataTable(table)) {
        const api = new $.fn.dataTable.Api(table);
        if (api.settings()[0].oFeatures.bServerSide && !api.settings()[0].json) return;
        const cell = table.querySelector('td.dataTables_empty');
        if (cell) decorateCell(table, cell, api.page.info().recordsTotal > 0);
        return;
      }
      const body = table.tBodies[0];
      if (body && body.children.length === 0 && !body.hasAttribute('data-attendance-rows')) {
        const cell = body.insertRow().insertCell();
        cell.colSpan = [...(table.tHead?.rows[0]?.cells || [])].reduce((sum, header) => sum + header.colSpan, 0) || 1;
        decorateCell(table, cell);
      }
      table.querySelectorAll('tbody > tr > td[colspan]').forEach(cell => {
        if (cell.parentElement.children.length === 1 && /^No\b/i.test(cell.textContent.trim())) decorateCell(table, cell);
      });
    });
    document.querySelectorAll('.content .st-empty').forEach(empty => {
      if (empty.dataset.uiEmpty) return;
      empty.dataset.uiEmpty = 'true';
      const message = empty.querySelector('p')?.textContent || description;
      empty.replaceChildren(makeEmpty(message)); empty.classList.remove('st-empty');
    });
    document.querySelectorAll('.st-evidence-grid > p').forEach(empty => {
      empty.replaceWith(makeEmpty(empty.textContent, false, false));
    });
  }
  normalizeAddButtons();
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', staticStates, {once: true});
  else staticStates();
})();
