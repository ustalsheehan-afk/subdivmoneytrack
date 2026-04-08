

<?php $__env->startSection('title', 'Payment'); ?>
<?php $__env->startSection('page-title', 'Payment: ' . ($type === 'penalty' ? $item->reason : $item->title)); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto p-6 lg:p-8">
    <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center shadow-sm">
            <i class="bi bi-check-circle-fill mr-3 text-green-500"></i>
            <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center shadow-sm">
            <i class="bi bi-exclamation-circle-fill mr-3 text-red-500"></i>
            <span class="text-sm font-medium"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <a href="<?php echo e(route('resident.payments.index')); ?>" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center mb-6 transition-colors">
        <i class="bi bi-arrow-left mr-1"></i> Back to Payments
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="bg-blue-600 text-white rounded-2xl p-8 shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-500 rounded-full opacity-50 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-blue-400 rounded-full opacity-30 blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-1">Pay via GCash</h3>
                    <p class="text-blue-100 text-sm">Scan the QR code or use the number below.</p>
                </div>

                <div class="my-8 flex flex-col items-center">
                    <div class="bg-white p-4 rounded-xl shadow-sm mb-4">
                        
                       <div class="w-48 h-48 bg-white rounded-lg overflow-hidden border border-gray-200">
    <img 
        src="<?php echo e(asset('images/gcash-qr.jpg')); ?>" 
        alt="GCash QR Code"
        class="w-full h-full object-contain"
    >
</div>
                    </div>

                    
                    <a href="<?php echo e(asset('images/gcash-qr.jpg')); ?>" download="GCash-QR.jpg" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-lg transition-all border border-white/20 backdrop-blur-sm">
                        <i class="bi bi-download"></i>
                        Download QR
                    </a>
                </div>

                <div>
                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-1">GCash Number</p>
                    <p class="text-2xl font-bold tracking-widest">0905 530 3469</p>
                    <p class="text-blue-200 text-sm mt-1">Account Name: Mussah Ustal</p>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Payment Details</h3>

            <div class="space-y-6">
                
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Amount Due</p>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest"><?php echo e($type === 'penalty' ? 'Penalty Fee' : 'Auto-computed Balance'); ?></p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">₱<?php echo e(number_format($type === 'penalty' ? $item->amount : $item->outstanding, 2)); ?></p>
                </div>

                <form action="<?php echo e(route('resident.payments.process', ['id' => $item->id, 'type' => $type])); ?>" method="POST" enctype="multipart/form-data" id="paymentForm">
                    <?php echo csrf_field(); ?>
                    
                    
                    <div class="mb-6">
                        <label class="text-sm font-medium text-gray-700">GCash Reference Number <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="reference_no" 
                               placeholder="Enter GCash Reference No." 
                               required
                               value="<?php echo e(old('reference_no')); ?>"
                               class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 <?php $__errorArgs = ['reference_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['reference_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Proof of Payment <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 transition-colors cursor-pointer" onclick="document.getElementById('proof').click()">
                            <div class="space-y-1 text-center">
                                <i class="bi bi-cloud-upload text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="proof" name="proof" type="file" class="sr-only" accept="image/*,application/pdf" required onchange="previewFile()">
                                    </span>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, PDF up to 5MB
                                </p>
                                <p id="fileName" class="text-sm text-green-600 font-medium mt-2 hidden"></p>
                                
                                
                                <div id="imagePreviewContainer" class="mt-4 hidden">
                                    <div class="relative inline-block">
                                        <img id="imagePreview" src="#" alt="Payment Proof Preview" class="max-h-48 rounded-lg border border-gray-200 shadow-sm">
                                        <button type="button" onclick="clearFile(event)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors">
                                            <i class="bi bi-x text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <button type="submit" class="w-full py-3 px-4 bg-[#385780] hover:bg-[#2B3A4F] text-white font-black text-sm uppercase tracking-widest rounded-xl shadow-sm transition-all flex items-center justify-center">
                        <i class="bi bi-check-circle-fill mr-2"></i> Submit Payment
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-4">
                        By submitting, you confirm that you have transferred the exact amount to the GCash number above.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewFile() {
        const fileInput = document.getElementById('proof');
        const fileNameDisplay = document.getElementById('fileName');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImage = document.getElementById('imagePreview');
        
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            fileNameDisplay.textContent = 'Selected: ' + file.name;
            fileNameDisplay.classList.remove('hidden');

            // Show image preview if it's an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        } else {
            fileNameDisplay.classList.add('hidden');
            previewContainer.classList.add('hidden');
        }
    }

    function clearFile(event) {
        event.stopPropagation(); // Prevent triggering the container click
        const fileInput = document.getElementById('proof');
        const fileNameDisplay = document.getElementById('fileName');
        const previewContainer = document.getElementById('imagePreviewContainer');
        
        fileInput.value = '';
        fileNameDisplay.classList.add('hidden');
        previewContainer.classList.add('hidden');
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\payments\pay.blade.php ENDPATH**/ ?>