(() => {
  'use strict';
  const token = new URLSearchParams(location.hash.slice(1)).get('token');
  const input = document.getElementById('reset_token');
  if (input && token && /^[a-f0-9]{64}$/.test(token)) input.value = token;
  if (location.hash) history.replaceState(null, '', location.pathname);
})();
