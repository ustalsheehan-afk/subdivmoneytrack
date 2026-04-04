
<?php $__env->startSection('title', 'My Requests'); ?>
<?php $__env->startSection('page-title', 'Service Requests'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8" x-data="{ filter: 'all' }">

        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.resident-hero-header','data' => ['label' => 'Resident Services','icon' => 'bi-inbox-fill','title' => 'Service Requests','description' => 'Track and manage your subdivision requests, maintenance reports, and service inquiries.','tabs' => [
                ['id' => 'all', 'label' => 'All', 'icon' => 'bi-grid-fill', 'click' => 'filter = \'all\'', 'active_condition' => 'filter === \'all\''],
                ['id' => 'pending', 'label' => 'Pending', 'icon' => 'bi-clock-history', 'click' => 'filter = \'pending\'', 'active_condition' => 'filter === \'pending\''],
                ['id' => 'in progress', 'label' => 'In Progress', 'icon' => 'bi-gear-fill', 'click' => 'filter = \'in progress\'', 'active_condition' => 'filter === \'in progress\''],
                ['id' => 'completed', 'label' => 'Completed', 'icon' => 'bi-check-circle-fill', 'click' => 'filter = \'completed\'', 'active_condition' => 'filter === \'completed\''],
            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('resident-hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Resident Services','icon' => 'bi-inbox-fill','title' => 'Service Requests','description' => 'Track and manage your subdivision requests, maintenance reports, and service inquiries.','tabs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                ['id' => 'all', 'label' => 'All', 'icon' => 'bi-grid-fill', 'click' => 'filter = \'all\'', 'active_condition' => 'filter === \'all\''],
                ['id' => 'pending', 'label' => 'Pending', 'icon' => 'bi-clock-history', 'click' => 'filter = \'pending\'', 'active_condition' => 'filter === \'pending\''],
                ['id' => 'in progress', 'label' => 'In Progress', 'icon' => 'bi-gear-fill', 'click' => 'filter = \'in progress\'', 'active_condition' => 'filter === \'in progress\''],
                ['id' => 'completed', 'label' => 'Completed', 'icon' => 'bi-check-circle-fill', 'click' => 'filter = \'completed\'', 'active_condition' => 'filter === \'completed\''],
            ])]); ?>
             <?php $__env->slot('actions', null, []); ?> 
                <a href="<?php echo e(route('resident.requests.create')); ?>" class="btn-premium">
                    <i class="bi bi-plus-lg"></i>
                    New Request
                </a>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

        
        
        
        <div class="glass-card overflow-hidden relative">
            
            <div class="overflow-x-auto relative z-10">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Request Details</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Timeline</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Current Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $statusMap = [
                                    'pending'    => ['bg' => 'bg-amber-500/10',   'text' => 'text-amber-600',   'border' => 'border-amber-500/20', 'icon' => 'bi-clock-history'],
                                    'in progress' => ['bg' => 'bg-blue-500/10',    'text' => 'text-blue-600',    'border' => 'border-blue-500/20',  'icon' => 'bi-gear-fill'],
                                    'completed'  => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600', 'border' => 'border-emerald-500/20', 'icon' => 'bi-check-circle-fill'],
                                    'approved'   => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600', 'border' => 'border-emerald-500/20', 'icon' => 'bi-shield-check'],
                                    'rejected'   => ['bg' => 'bg-red-500/10',     'text' => 'text-red-600',     'border' => 'border-red-500/20',     'icon' => 'bi-x-circle-fill'],
                                ];
                                $style = $statusMap[strtolower($request->status)] ?? ['bg' => 'bg-gray-500/10', 'text' => 'text-gray-600', 'border' => 'border-gray-500/20', 'icon' => 'bi-info-circle'];
                            ?>

                            <tr class="group hover:bg-gray-50/80 transition-all duration-300 cursor-pointer" 
                                onclick="window.location='<?php echo e(route('resident.requests.show', $request->id)); ?>'"
                                x-show="filter === 'all' || filter === '<?php echo e(strtolower($request->status)); ?>'">
                                
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="relative shrink-0">
                                            <?php if($request->photo): ?>
                                                <img src="<?php echo e(asset('storage/' . $request->photo)); ?>" 
                                                     class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-md group-hover:scale-110 transition-transform duration-500">
                                            <?php else: ?>
                                                <div class="w-14 h-14 rounded-2xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors">
                                                    <i class="bi bi-image text-xl"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-gray-900 tracking-tight capitalize mb-1"><?php echo e($request->type); ?></p>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest line-clamp-1 group-hover:text-gray-600 transition-colors">
                                                <?php echo e($request->description ?: 'No description provided'); ?>

                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900 tracking-tight"><?php echo e($request->created_at->format('M d, Y')); ?></span>
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1"><?php echo e($request->created_at->format('h:i A')); ?></span>
                                    </div>
                                </td>

                                <td class="px-8 py-6 text-center">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border <?php echo e($style['bg']); ?> <?php echo e($style['text']); ?> <?php echo e($style['border']); ?> shadow-sm">
                                        <i class="bi <?php echo e($style['icon']); ?> text-xs"></i>
                                        <?php echo e($request->status); ?>

                                    </span>
                                </td>

                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3" onclick="event.stopPropagation()">
                                        <a href="<?php echo e(route('resident.requests.show', $request->id)); ?>" 
                                           class="w-10 h-10 rounded-xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-500 hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-500/10 flex items-center justify-center transition-all duration-300"
                                           title="View Details">
                                            <i class="bi bi-eye-fill text-sm"></i>
                                        </a>
                                        <?php if($request->status == 'pending'): ?>
                                        <a href="<?php echo e(route('resident.requests.edit', $request->id)); ?>" 
                                           class="w-10 h-10 rounded-xl bg-white border border-gray-100 text-gray-400 hover:text-amber-500 hover:border-amber-500 hover:shadow-lg hover:shadow-amber-500/10 flex items-center justify-center transition-all duration-300"
                                           title="Edit Request">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-8 py-24 text-center">
                                    <div class="relative inline-block mb-6">
                                        <div class="absolute inset-0 bg-emerald-500/10 rounded-full blur-2xl animate-pulse"></div>
                                        <div class="relative w-20 h-20 bg-gray-50 rounded-full border border-dashed border-gray-200 flex items-center justify-center mx-auto">
                                            <i class="bi bi-inbox text-3xl text-gray-300"></i>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight mb-2">No Service Requests</h3>
                                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest max-w-xs mx-auto">
                                        You haven't submitted any service requests yet.
                                    </p>
                                    <div class="mt-8">
                                        <a href="<?php echo e(route('resident.requests.create')); ?>" 
                                           class="inline-flex items-center gap-3 px-8 py-4 bg-emerald-500 text-black text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-400 transition-all duration-300">
                                            Submit Your First Request
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/resident/requests/index.blade.php ENDPATH**/ ?>