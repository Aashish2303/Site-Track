<aside class="st-auth-story">
    <a class="st-brand" href="{{ route('user.login') }}">@include('user.partials.brand')</a>
    <div class="st-auth-story-content">
        <span class="st-auth-tag"><i class="ti ti-building-community" aria-hidden="true"></i> A clearer way to build</span>
        <h1>Good work starts<br>with a <em>clear view.</em></h1>
        <p>Your projects, materials, and teams. One connected workspace to keep every detail moving in the right direction.</p>
        <div class="st-blueprint" aria-hidden="true">
            <div class="st-blueprint-buildings"><span></span><span></span><span></span><span></span></div>
            <span class="st-blueprint-label">PLAN &nbsp;/&nbsp; TRACK &nbsp;/&nbsp; DELIVER</span>
        </div>
    </div>
    <div class="st-auth-note"><span>Built for the work ahead.</span><span>&copy; {{ date('Y') }} {{ Request()->settings->settings_title }}</span></div>
</aside>
