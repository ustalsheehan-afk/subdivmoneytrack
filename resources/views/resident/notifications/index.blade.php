@extends('resident.layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="space-y-6" x-data="residentNotifications()" x-init="loadNotifications()">
    <div class="relative overflow-hidden rounded-[1.75rem] bg-[#081412] px-6 py-8 sm:px-8 sm:py-10 shadow-xl shadow-emerald-900/15">
        <div class="absolute inset-0 bg-gradient-to-br from-[#081412] via-[#0D1F1C] to-[#132520]"></div>
        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-[rgba(182,255,92,0.12)] blur-3xl"></div>
        <div class="absolute -left-8 bottom-0 h-32 w-32 rounded-full bg-white/5 blur-3xl"></div>

        <div class="relative z-10 flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-300/80 mb-3">Resident Notifications</p>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white leading-tight">All notifications in one place.</h1>
                <p class="mt-3 text-sm sm:text-[15px] text-white/70 leading-relaxed">
                    Review every subdivision update, latest first. Notifications are loaded from the backend API and marked as read when this page opens.
                </p>
            </div>

            <div class="grid w-full gap-3 sm:grid-cols-3 xl:w-auto xl:min-w-[560px] xl:grid-cols-[auto_auto_auto]">
                <div class="flex min-w-[110px] items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-left sm:justify-start sm:gap-4 xl:min-w-[140px]">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/50">Total</p>
                    <p class="mt-1 text-2xl font-black text-white" x-text="notifications.length"></p>
                </div>
                <button
                    type="button"
                    @click="markAllRead()"
                    class="inline-flex min-w-[180px] items-center justify-center gap-2 rounded-2xl bg-[var(--brand-accent)] px-5 py-3 text-[11px] font-black uppercase tracking-[0.18em] text-[#081412] transition-transform hover:-translate-y-0.5"
                >
                    <i class="bi bi-check2-all text-base"></i>
                    Mark all as read
                </button>
                <a href="{{ route('resident.home') }}" class="inline-flex min-w-[180px] items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-[11px] font-black uppercase tracking-[0.18em] text-white/80 transition-colors hover:bg-white/10">
                    <i class="bi bi-arrow-left"></i>
                    Back to dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/40 px-6 py-4 sm:px-8">
            <div>
                <h2 class="text-sm font-black uppercase tracking-[0.22em] text-gray-900">Notifications Feed</h2>
                <p class="mt-1 text-[12px] font-medium text-gray-500">Latest notifications appear first.</p>
            </div>
            <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.18em] text-gray-500">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500" x-show="!loading && !error"></span>
                <span x-text="loading ? 'Loading' : (error ? 'Error' : 'Live')"></span>
            </div>
        </div>

        <div class="px-4 py-4 sm:px-6 sm:py-6">
            <template x-if="loading">
                <div class="py-20 text-center">
                    <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-4 border-gray-100 border-t-[#081412]"></div>
                    <p class="text-sm font-semibold text-gray-500">Loading notifications...</p>
                </div>
            </template>

            <template x-if="error">
                <div class="rounded-3xl border border-rose-100 bg-rose-50 px-6 py-5 text-sm font-semibold text-rose-700">
                    <span x-text="error"></span>
                </div>
            </template>

            <template x-if="!loading && !error && notifications.length === 0">
                <div class="py-20 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-50 text-gray-300">
                        <i class="bi bi-bell-slash text-2xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-500">No notifications available.</p>
                </div>
            </template>

            <div class="space-y-4" x-show="!loading && !error && notifications.length > 0" x-cloak>
                <template x-for="notification in notifications" :key="notification.id">
                    <a
                        :href="notification.link ? notification.link : '#'
                        "
                        class="group block rounded-[1.5rem] border border-gray-100 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:border-emerald-100 hover:shadow-md"
                    >
                        <div class="flex gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl" :class="iconBg(notification.category)">
                                <i class="bi text-xl" :class="iconClass(notification.category)"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="truncate text-[15px] font-black text-gray-900" x-text="notification.title"></h3>
                                            <span
                                                x-show="!notification.is_read"
                                                class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-emerald-700"
                                            >
                                                New
                                            </span>
                                        </div>
                                        <p class="mt-1 text-[13px] leading-relaxed text-gray-600" x-text="notification.message"></p>
                                    </div>
                                    <div class="shrink-0 text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400" x-text="formatDate(notification.created_at)"></div>
                                </div>

                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    <span class="rounded-full bg-gray-50 px-3 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-gray-500" x-text="notification.category || 'general'"></span>
                                    <span class="text-[11px] font-semibold text-gray-400" x-show="notification.link">Tap to open related item</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    function residentNotifications() {
        return {
            notifications: [],
            loading: true,
            error: '',
            async loadNotifications() {
                this.loading = true;
                this.error = '';

                try {
                    const response = await fetch('{{ route('resident.notifications.api') }}', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Unable to load notifications.');
                    }

                    const data = await response.json();
                    this.notifications = Array.isArray(data.notifications) ? data.notifications : [];
                } catch (exception) {
                    this.error = exception?.message || 'Unable to load notifications.';
                    this.notifications = [];
                } finally {
                    this.loading = false;
                }
            },
            async markAllRead() {
                try {
                    await fetch('{{ route('resident.notifications.mark-all-read') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    await this.loadNotifications();
                } catch (exception) {
                    this.error = exception?.message || 'Unable to update notifications.';
                }
            },
            formatDate(value) {
                if (!value) {
                    return '';
                }

                const date = new Date(value);
                if (Number.isNaN(date.getTime())) {
                    return '';
                }

                return new Intl.DateTimeFormat('en', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }).format(date);
            },
            iconClass(category) {
                const normalized = (category || '').toLowerCase();

                if (normalized === 'payment') {
                    return 'bi-cash-stack text-blue-500';
                }

                if (normalized === 'billing') {
                    return 'bi-receipt text-indigo-500';
                }

                if (normalized === 'reminder') {
                    return 'bi-exclamation-circle text-amber-500';
                }

                if (normalized === 'request') {
                    return 'bi-tools text-emerald-600';
                }

                if (normalized === 'alert') {
                    return 'bi-exclamation-triangle text-orange-500';
                }

                return 'bi-bell text-gray-500';
            },
            iconBg(category) {
                const normalized = (category || '').toLowerCase();

                if (normalized === 'payment') {
                    return 'bg-blue-50';
                }

                if (normalized === 'billing') {
                    return 'bg-indigo-50';
                }

                if (normalized === 'reminder') {
                    return 'bg-amber-50';
                }

                if (normalized === 'request') {
                    return 'bg-emerald-50';
                }

                if (normalized === 'alert') {
                    return 'bg-orange-50';
                }

                return 'bg-gray-50';
            }
        };
    }
</script>
@endsection
