

<?php $__env->startSection('title', 'View Request'); ?>
<?php $__env->startSection('page-title', 'Request Details'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-6xl mx-auto space-y-6">


<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 flex items-center justify-between relative overflow-hidden">

<div class="absolute left-0 top-0 bottom-0 w-1 bg-[#0D1F1C] rounded-l-2xl"></div>

<div class="pl-3">

<div class="flex items-center gap-3 mb-1">

<span class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100">
Service Ticket
</span>

<span class="text-xs font-semibold text-gray-400">
#<?php echo e(str_pad($request->id,4,'0',STR_PAD_LEFT)); ?>

</span>

</div>

<h2 class="text-2xl font-bold text-gray-900 capitalize">
<?php echo e(ucfirst($request->type)); ?>

</h2>

<p class="text-sm text-gray-500 mt-1">
Submitted <?php echo e($request->created_at->diffForHumans()); ?>

</p>

</div>

<a href="<?php echo e(route('admin.requests.index')); ?>"
class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg border border-gray-200 transition">
<i class="bi bi-arrow-left"></i>
Back
</a>

</div>



<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">



<div class="lg:col-span-2 space-y-6">


<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

<h3 class="font-semibold text-gray-800 mb-6 flex items-center gap-2">
<i class="bi bi-clock-history text-[#0D1F1C]"></i>
Request Progress
</h3>

<div class="relative pl-8 space-y-6">

<div class="absolute left-[12px] top-2 bottom-2 w-[2px] bg-gray-100"></div>


<div class="relative">

<div class="absolute -left-[26px] top-1 w-4 h-4 rounded-full bg-emerald-500 border-4 border-white shadow"></div>

<p class="text-sm font-semibold text-gray-900">
Request Submitted
</p>

<p class="text-xs text-emerald-600 font-medium mt-1">
<?php echo e($request->created_at->format('F d, Y • g:i A')); ?>

</p>

<p class="text-xs text-gray-500 mt-1">
Initial request submitted by <?php echo e($request->resident->first_name); ?>

</p>

</div>



<?php
$isInProgress = in_array($request->status,['in progress','completed']);
$progressDate = $request->processed_at ?? ($isInProgress ? $request->updated_at : null);
?>

<div class="relative <?php echo e(!$isInProgress ? 'opacity-50' : ''); ?>">

<div class="absolute -left-[26px] top-1 w-4 h-4 rounded-full <?php echo e($isInProgress ? 'bg-amber-500' : 'bg-gray-300'); ?> border-4 border-white shadow"></div>

<p class="text-sm font-semibold text-gray-900">
Request In Progress
</p>

<?php if($progressDate): ?>
<p class="text-xs text-amber-600 font-medium mt-1">
<?php echo e($progressDate->format('F d, Y • g:i A')); ?>

</p>
<?php endif; ?>

<p class="text-xs text-gray-500 mt-1">
Maintenance team started processing this ticket
</p>

</div>



<?php
$isCompleted = $request->status == 'completed';
$completedDate = $request->completed_at ?? ($isCompleted ? $request->updated_at : null);
?>

<div class="relative <?php echo e(!$isCompleted ? 'opacity-50' : ''); ?>">

<div class="absolute -left-[26px] top-1 w-4 h-4 rounded-full <?php echo e($isCompleted ? 'bg-emerald-500' : 'bg-gray-300'); ?> border-4 border-white shadow"></div>

<p class="text-sm font-semibold text-gray-900">
Request Completed
</p>

<?php if($completedDate): ?>
<p class="text-xs text-emerald-600 font-medium mt-1">
<?php echo e($completedDate->format('F d, Y • g:i A')); ?>

</p>
<?php endif; ?>

<p class="text-xs text-gray-500 mt-1">
Issue resolved and verified by the administration
</p>

</div>

</div>

</div>



<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

<h3 class="font-semibold text-gray-800 mb-4">
Request Description
</h3>

<div class="bg-gray-50 border border-gray-100 rounded-xl p-5 text-gray-700 leading-relaxed">
<?php echo e($request->description); ?>

</div>

<?php if($request->photo): ?>
<div class="mt-6 space-y-3">

<h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">
Attached Photo
</h4>

<div class="relative group inline-block">

<img src="<?php echo e(asset('storage/' . $request->photo)); ?>"
class="max-h-[400px] w-auto rounded-xl shadow-md border border-gray-200 cursor-zoom-in hover:shadow-lg transition"
onclick="window.open(this.src,'_blank')">

<div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition rounded-xl flex items-center justify-center text-white text-xs font-semibold">
<i class="bi bi-zoom-in mr-2"></i> View Image
</div>

</div>

</div>
<?php endif; ?>

</div>

</div>




<div class="space-y-6">


<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

<h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
Resident Information
</h3>

<div class="flex items-center gap-4">

<img
src="<?php echo e($request->resident->photo ? asset('storage/'.$request->resident->photo) : asset('CDlogo.jpg')); ?>"
class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-200">

<div>
<p class="font-semibold text-gray-900">
<?php echo e($request->resident->full_name); ?>

</p>

<p class="text-sm text-gray-500">
Block <?php echo e($request->resident->block); ?> • Lot <?php echo e($request->resident->lot); ?>

</p>
</div>

</div>

</div>



<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-4">

<h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
Request Details
</h3>

<div class="flex justify-between">

<span class="text-gray-500 text-sm">Priority</span>

<span class="px-3 py-1 text-xs rounded-full font-semibold
<?php if($request->priority == 'High'): ?> bg-red-100 text-red-700
<?php elseif($request->priority == 'Medium'): ?> bg-yellow-100 text-yellow-700
<?php else: ?> bg-green-100 text-green-700
<?php endif; ?>">
<?php echo e($request->priority); ?>

</span>

</div>


<div class="flex justify-between">

<span class="text-gray-500 text-sm">Status</span>

<span class="px-3 py-1 text-xs rounded-full font-semibold
<?php if($request->status == 'pending'): ?> bg-gray-200 text-gray-700
<?php elseif($request->status == 'in progress'): ?> bg-emerald-100 text-emerald-700
<?php elseif($request->status == 'completed'): ?> bg-green-100 text-green-700
<?php endif; ?>">
<?php echo e(ucfirst($request->status)); ?>

</span>

</div>


<div class="flex justify-between">

<span class="text-gray-500 text-sm">Submitted</span>

<span class="text-gray-800 text-sm font-medium">
<?php echo e($request->created_at->format('M d, Y')); ?>

</span>

</div>

</div>




<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

<h3 class="font-semibold text-gray-800 mb-4">
Update Status
</h3>

<form action="<?php echo e(route('admin.requests.updateStatus', $request->id)); ?>" method="POST" class="space-y-3">

<?php echo csrf_field(); ?>

<select name="status"
class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0D1F1C] focus:border-[#0D1F1C]">

<option value="pending" <?php echo e($request->status == 'pending' ? 'selected' : ''); ?>>Pending</option>

<option value="in progress" <?php echo e($request->status == 'in progress' ? 'selected' : ''); ?>>In Progress</option>

<option value="completed" <?php echo e($request->status == 'completed' ? 'selected' : ''); ?>>Completed</option>

</select>

<button
class="w-full bg-[#0D1F1C] hover:bg-emerald-900 text-white text-sm py-2 rounded-lg font-medium transition">
Save Changes
</button>

</form>

</div>

</div>

</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\requests\show.blade.php ENDPATH**/ ?>