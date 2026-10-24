(() => {
  'use strict';
  const allowed = new Set(['select_materials','select_material_unit','select_project','add_boq_item','edit_boq_item','select_boq','select_work_done','filter_options','more_material','remove_material','indent_item_status','confirm_reject_msg','confirm_msg','select_filter','show_password','edit_category','daily_progress_status','daily_work_status','edit_issue_category','issue_status','edit_material_category','order_pay_status','project_status','edit_unit']);
  const invoke = (event, attribute) => {
    const element = event.target.closest(`[${attribute}]`);
    if (!element) return;
    const match = element.getAttribute(attribute).match(/^([a-z_]+)\((.*)\)$/);
    if (!match || !allowed.has(match[1]) || typeof window[match[1]] !== 'function') return;
    const proxy = new Proxy(event, {get(target, key) {
      if (key === 'currentTarget') return element;
      const value = Reflect.get(target, key, target);
      return typeof value === 'function' ? value.bind(target) : value;
    }});
    const args = [];
    const text = match[2].trim();
    if (text) {
      for (const item of text.split(',')) {
        const arg = item.trim();
        if (arg === 'event') args.push(proxy);
        else if (arg === 'this') args.push(element);
        else if (/^\d+$/.test(arg)) args.push(Number(arg));
        else if (/^'[-A-Za-z0-9_]*'$/.test(arg)) args.push(arg.slice(1, -1));
        else return;
      }
    }
    if (event.type === 'click') event.preventDefault();
    window[match[1]](...args);
  };
  const click = event => {
    if (event.target.closest('a[href="#"]')) event.preventDefault();
    invoke(event, 'data-st-click');
  };
  // Select2 and existing forms emit jQuery changes; handle them as well as native events.
  if (window.jQuery) {
    window.jQuery(document).on('click.stSecurity', click);
    window.jQuery(document).on('change.stSecurity', event => invoke(event, 'data-st-change'));
  } else {
    document.addEventListener('click', click);
    document.addEventListener('change', event => invoke(event, 'data-st-change'));
  }
})();
