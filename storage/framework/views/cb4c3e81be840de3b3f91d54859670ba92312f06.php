<?php $__env->startSection('title', 'Roles & Permissions'); ?>
<?php $__env->startSection('page-title', 'Roles & Permissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in" x-data="rolesPermissions({
    roles: <?php echo \Illuminate\Support\Js::from($roles->map(fn($r) => [
        'id' => $r->id,
        'name' => $r->name,
        'permissions' => $r->permissions->map(fn($p) => $p->key)->filter()->values(),
    ])->values())->toHtml() ?>,
    permissions: <?php echo \Illuminate\Support\Js::from($permissions->map(fn($p) => $p->key)->filter()->values())->toHtml() ?>,
})">
    <div class="glass-card p-8 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">Roles & Permissions</h1>
                <p class="mt-2 text-gray-600 text-sm max-w-2xl">Assign capabilities to roles. All checks are permission-key based and evaluated dynamically from the database.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                    <i class="bi bi-shield-lock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="glass-card p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="text-sm font-bold text-gray-700">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Roles</span>
                <div class="mt-1">Toggle permissions per role. Changes apply immediately.</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="px-4 py-2 rounded-xl bg-gray-50 border border-gray-200 text-[10px] font-black uppercase tracking-widest text-gray-600">
                    <span x-text="roles.length"></span> Roles
                </div>
                <div class="px-4 py-2 rounded-xl bg-gray-50 border border-gray-200 text-[10px] font-black uppercase tracking-widest text-gray-600">
                    <span x-text="permissions.length"></span> Permissions
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <template x-for="role in roles" :key="role.id">
            <div class="glass-card overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gray-900 text-[#B6FF5C] flex items-center justify-center text-sm font-black uppercase" x-text="role.name.replaceAll('_', ' ').split(' ').map(w => w[0]).join('')"></div>
                        <div>
                            <div class="text-lg font-black text-gray-900 uppercase tracking-tight" x-text="role.name.replaceAll('_', ' ')"></div>
                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <span x-text="role.permissions.length"></span> Enabled
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full border text-[9px] font-black uppercase tracking-widest"
                              :class="role.permissions.includes('*') ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-white border-gray-200 text-gray-500'"
                              x-text="role.permissions.includes('*') ? 'Full Access' : 'Scoped'"></span>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <template x-for="moduleKey in moduleOrder" :key="moduleKey">
                        <div class="p-5 rounded-2xl border border-gray-100 bg-white">
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div class="text-sm font-black text-gray-900 uppercase tracking-tight" x-text="toTitle(moduleKey)"></div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <span x-text="countEnabled(role, moduleKey)"></span>/<span x-text="(grouped[moduleKey] || []).length"></span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="actionKey in actionsFor(moduleKey)" :key="moduleKey + '.' + actionKey">
                                    <label class="flex items-center gap-2 px-4 py-2 rounded-full border transition-all cursor-pointer"
                                           :class="role.permissions.includes(moduleKey + '.' + actionKey) ? 'bg-emerald-50 border-emerald-100 text-emerald-800' : 'bg-gray-50 border-gray-100 text-gray-600 hover:bg-white hover:border-gray-200'">
                                        <input type="checkbox"
                                               class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/10"
                                               :checked="role.permissions.includes(moduleKey + '.' + actionKey)"
                                               @change="toggle(role.id, moduleKey + '.' + actionKey, $event.target.checked)">
                                        <span class="text-[10px] font-black uppercase tracking-widest" x-text="actionKey"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="legacyPermissions.length > 0">
                        <div class="p-5 rounded-2xl border border-gray-100 bg-white">
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div class="text-sm font-black text-gray-900 uppercase tracking-tight">Legacy</div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Backward Compatibility</div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <template x-for="permKey in legacyPermissions" :key="permKey">
                                    <label class="flex items-center justify-between gap-3 p-4 rounded-2xl border transition-all cursor-pointer"
                                           :class="role.permissions.includes(permKey) ? 'bg-emerald-50/40 border-emerald-100' : 'bg-white border-gray-100 hover:border-gray-200'">
                                        <div class="min-w-0">
                                            <div class="text-sm font-black text-gray-900" x-text="toLabel(permKey)"></div>
                                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate" x-text="permKey"></div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border"
                                                  :class="role.permissions.includes(permKey) ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-gray-50 text-gray-500 border-gray-100'"
                                                  x-text="role.permissions.includes(permKey) ? 'Allowed' : 'Disabled'"></span>
                                            <input type="checkbox"
                                                   class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/10"
                                                   :checked="role.permissions.includes(permKey)"
                                                   @change="toggle(role.id, permKey, $event.target.checked)">
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
function rolesPermissions(init) {
    return {
        roles: init.roles,
        permissions: init.permissions,
        actionOrder: ['view','create','update','delete','approve','reject','export','manage','record','resend','reply','close','send','cancel'],
        moduleOrder: [
            'dashboard',
            'announcements',
            'support',
            'notifications',
            'residents',
            'invitations',
            'dues',
            'payments',
            'penalties',
            'requests',
            'amenities',
            'reservations',
            'board_members',
            'reports',
            'logs',
            'roles',
            'users',
        ],
        get grouped() {
            const map = {}
            for (const key of this.permissions) {
                if (!key || key === '*' || !key.includes('.')) continue
                const [moduleKey, actionKey] = key.split('.', 2)
                if (!moduleKey || !actionKey) continue
                if (!map[moduleKey]) map[moduleKey] = []
                if (!map[moduleKey].includes(actionKey)) map[moduleKey].push(actionKey)
            }
            return map
        },
        get legacyPermissions() {
            return this.permissions.filter(k => k && k !== '*' && !k.includes('.'))
        },
        actionsFor(moduleKey) {
            const actions = (this.grouped[moduleKey] || []).slice()
            actions.sort((a, b) => {
                const ai = this.actionOrder.indexOf(a)
                const bi = this.actionOrder.indexOf(b)
                if (ai === -1 && bi === -1) return a.localeCompare(b)
                if (ai === -1) return 1
                if (bi === -1) return -1
                return ai - bi
            })
            return actions
        },
        countEnabled(role, moduleKey) {
            const actions = this.grouped[moduleKey] || []
            let c = 0
            for (const a of actions) {
                if (role.permissions.includes(moduleKey + '.' + a)) c++
            }
            return c
        },
        toLabel(key) {
            return key.replaceAll('_', ' ').replaceAll('-', ' ').replace(/\b\w/g, c => c.toUpperCase())
        },
        toTitle(key) {
            return key.replaceAll('_', ' ').replaceAll('-', ' ').replace(/\b\w/g, c => c.toUpperCase())
        },
        async toggle(roleId, permissionKey, enabled) {
            const role = this.roles.find(r => r.id === roleId)
            if (!role) return

            const prev = role.permissions.slice()

            if (enabled) {
                if (!role.permissions.includes(permissionKey)) role.permissions.push(permissionKey)
            } else {
                role.permissions = role.permissions.filter(k => k !== permissionKey)
            }

            try {
                const res = await fetch(`<?php echo e(url('admin/system/roles-permissions')); ?>/${roleId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ permission_key: permissionKey, enabled }),
                })
                const data = await res.json().catch(() => ({}))
                if (!res.ok || data.success === false) {
                    role.permissions = prev
                    alert(data.message || 'Unable to update permissions.')
                }
            } catch (e) {
                role.permissions = prev
                alert('Unable to update permissions.')
            }
        },
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\system\roles-permissions.blade.php ENDPATH**/ ?>