<!-- Form Textarea Component -->
<div>
    <label for="<?php echo e($id ?? $name); ?>" class="block text-sm font-medium text-gray-700 mb-1.5">
        <?php echo e($label ?? ucfirst($name)); ?>

        <?php if($required ?? false): ?>
            <span class="text-red-500">*</span>
        <?php endif; ?>
    </label>
    
    <textarea 
        id="<?php echo e($id ?? $name); ?>"
        name="<?php echo e($name); ?>"
        rows="<?php echo e($rows ?? 4); ?>"
        placeholder="<?php echo e($placeholder ?? ''); ?>"
        class="w-full px-4 py-2.5 text-sm rounded-lg border-2 transition-all focus:outline-none focus:ring-2 focus:ring-offset-0 resize-none
            <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500
            <?php else: ?>
                border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-blue-500
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        <?php if($required ?? false): ?> required <?php endif; ?>
        <?php if($disabled ?? false): ?> disabled <?php endif; ?>
    ><?php echo e(old($name, $value ?? '')); ?></textarea>
    
    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="mt-1.5 text-sm text-red-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.101 8.101a1 1 0 001.414 0l9.101-9.101z" clip-rule="evenodd"/>
            </svg>
            <?php echo e($message); ?>

        </p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\components\form-textarea.blade.php ENDPATH**/ ?>