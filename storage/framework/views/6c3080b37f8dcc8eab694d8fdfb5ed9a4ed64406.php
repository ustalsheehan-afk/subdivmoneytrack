

<?php $__env->startSection('title', 'Subdivision Amenities'); ?>
<?php $__env->startSection('page-title', 'Subdivision Amenities'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $amenities = [
        ['title'=>'Clubhouse','category'=>'Community','desc'=>'Ideal for events, meetings, and community gatherings.','img'=>asset('images/clubhouse.jpg')],
        ['title'=>'Basketball Court','category'=>'Sports','desc'=>'Open for practice, friendly games, and tournaments.','img'=>asset('images/subdivision-clubhouse.jpg')],
        ['title'=>'Swimming Pool','category'=>'Recreation','desc'=>'A relaxing space for families and residents.','img'=>asset('images/subdivision-event.jpg')],
        ['title'=>'Children’s Playground','category'=>'Kids','desc'=>'Safe and fun outdoor play area for kids.','img'=>asset('images/subdivision-playground.jpg')],
        ['title'=>'Subdivision Houses','category'=>'Living','desc'=>'A view of our beautifully designed homes within the community.','img'=>asset('images/subdivision-hero1.jpg')],
        ['title'=>'Open Spaces','category'=>'Nature','desc'=>'Green areas for walking, relaxation, and community activities.','img'=>asset('images/open.jpg')],
    ];
?>

<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col gap-12 pb-24">

        
        <div class="relative overflow-hidden bg-[#081412] rounded-[40px] p-12 shadow-2xl group animate-fade-in">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-accent/10 rounded-full blur-3xl group-hover:bg-brand-accent/20 transition-all duration-1000"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
            
            <div class="max-w-3xl relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                    <i class="bi bi-building-fill text-emerald-400 text-xs"></i>
                    <span class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.2em]">Community Facilities</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                    Subdivision Amenities
                </h2>
                <p class="text-[15px] font-medium text-white/50 leading-relaxed">
                   Built for convenience. Maintained for excellence. Vistabellas provides top-tier infrastructure and well-maintained facilities for residents' convenience.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group relative h-[450px] bg-[#081412] border border-white/5 shadow-2xl overflow-hidden rounded-[32px] hover:shadow-[0_20px_50px_rgba(0,0,0,0.3)] transition-all duration-500 hover:-translate-y-2">
                    <!-- Image -->
                    <div class="absolute inset-0 w-full h-full bg-white/5">
                        <img 
                            src="<?php echo e($amenity['img']); ?>" 
                            alt="<?php echo e($amenity['title']); ?>" 
                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-70 group-hover:opacity-90"
                        >
                    </div>

                    <!-- Dark Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081412] via-[#081412]/60 to-transparent transition-opacity duration-500 opacity-90 group-hover:opacity-95"></div>

                    <!-- Accent Blur -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>

                    <!-- Content Panel -->
                    <div class="absolute bottom-0 left-0 right-0 p-8 translate-y-8 group-hover:translate-y-0 transition-all duration-500 ease-out">
                        <div class="space-y-2">
                            <h3 class="text-2xl font-black text-white tracking-tight">
                                <?php echo e($amenity['title']); ?>

                            </h3>
                            <p class="text-[10px] font-black tracking-[0.2em] text-emerald-400 uppercase">
                                <?php echo e($amenity['category']); ?>

                            </p>
                        </div>
                        
                        <div class="mt-6 opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100 space-y-4">
                            <p class="text-sm text-white/60 leading-relaxed font-medium">
                                <?php echo e($amenity['desc']); ?>

                            </p>
                            
                            <div class="pt-2">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                                    <i class="bi bi-check2-circle"></i> Available for use
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #CBD5E0; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\about\amenities.blade.php ENDPATH**/ ?>