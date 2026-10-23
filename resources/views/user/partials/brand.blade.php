@if(!empty(Request()->settings->settings_wlogo) && is_file(public_path(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_wlogo)))
<span class="st-brand-copy st-brand-configured">
    <img class="st-brand-logo st-logo-on-dark" src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_wlogo) }}" alt="{{ Request()->settings->settings_title }}">
    <img class="st-brand-logo st-logo-on-light" src="{{ asset(config('constants.admin_path').'uploads/logo/'.(Request()->settings->settings_logo ?: Request()->settings->settings_wlogo)) }}" alt="{{ Request()->settings->settings_title }}">
    <small>Project workspace</small>
</span>
@else
<span class="st-brand-mark" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none"><path d="M4 19V9l8-5 8 5v10M8 19v-7h8v7M3 19h18M12 4v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
</span>
<span class="st-brand-copy"><strong>{{ Request()->settings->settings_title }}</strong><small>Project workspace</small></span>
@endif
