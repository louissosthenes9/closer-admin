<style>
    /* Give navigation its own surface while retaining the panel's amber accent. */
    .fi-sidebar, .fi-sidebar-header { background: #f2f3f1; }
    .fi-sidebar { border-inline-end: 1px solid #d9ddd7; }
    .fi-sidebar-header { height: 3.5rem; border-bottom: 1px solid #d9ddd7; }
    .fi-sidebar-nav { padding-inline: .75rem; row-gap: .75rem; }
    .fi-sidebar-item-btn { padding: .5rem .625rem; font-size: .8125rem; border-radius: .5rem; }
    .fi-sidebar-item-btn:hover { background: #e3e7df; }
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn { background: #fff; box-shadow: 0 1px 3px rgb(30 41 30 / .12), 0 0 0 1px #d8ddd3; }
    .fi-sidebar-item-icon { width: 1.125rem; height: 1.125rem; }
    .fi-sidebar-group-label { font-size: .6875rem; letter-spacing: .06em; text-transform: uppercase; }

    /* Make field edges visible against the page and section surfaces. */
    .fi-input-wrp { box-shadow: 0 0 0 1px #b8c1b6, 0 1px 2px rgb(30 41 30 / .05); border-radius: .5rem; }
    .fi-input-wrp:focus-within { box-shadow: 0 0 0 2px #d97706; }
    .fi-input-wrp.fi-invalid { box-shadow: 0 0 0 1px #dc2626; }
    .fi-section:not(.fi-section-not-contained) { border: 1px solid #dce2da; border-radius: .875rem; }

    .fi-wi-stats-overview-stat, .fi-wi-chart, .fi-ta-ctn { border-radius: .875rem; }
    .fi-wi-stats-overview-stat-value, .fi-header-heading { letter-spacing: -.02em; }

    .dark .fi-sidebar, .dark .fi-sidebar-header { background: #202821; }
    .dark .fi-sidebar, .dark .fi-sidebar-header { border-color: #39453a; }
    .dark .fi-sidebar-item-btn:hover { background: #303c31; }
    .dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn { background: #39463a; box-shadow: 0 0 0 1px #526153; }
    .dark .fi-input-wrp { box-shadow: 0 0 0 1px #657367; }
    .dark .fi-input-wrp:focus-within { box-shadow: 0 0 0 2px #f59e0b; }
    .dark .fi-input-wrp.fi-invalid { box-shadow: 0 0 0 1px #ef4444; }
    .dark .fi-section:not(.fi-section-not-contained) { border-color: #39453a; }
</style>
