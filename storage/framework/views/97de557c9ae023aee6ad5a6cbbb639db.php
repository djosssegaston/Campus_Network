<!-- Card Component for Auth Forms -->
<div class="bg-white rounded-2xl shadow-2xl overflow-hidden backdrop-blur-lg">
    <!-- Header -->
    <div class="bg-gradient-to-r <?php echo e($gradientFrom ?? 'from-blue-600'); ?> <?php echo e($gradientTo ?? 'to-blue-700'); ?> px-6 py-8 sm:px-8">
        <div class="text-center">
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2"><?php echo e($title ?? 'Campus Network'); ?></h1>
            <p class="text-blue-100 text-sm sm:text-base"><?php echo e($subtitle ?? ''); ?></p>
        </div>
    </div>
    
    <!-- Content -->
    <div class="px-6 py-8 sm:px-8">
        <?php echo e($slot); ?>

    </div>
    
    <?php if($footer ?? false): ?>
        <!-- Footer -->
        <div class="px-6 py-4 sm:px-8 bg-gray-50 border-t border-gray-200">
            <p class="text-sm text-gray-600 text-center">
                <?php echo e($footerText ?? ''); ?>

                <?php if($footerLink ?? false): ?>
                    <a href="<?php echo e($footerLink); ?>" class="font-semibold text-blue-600 hover:text-blue-700 transition">
                        <?php echo e($footerLinkText ?? ''); ?>

                    </a>
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\HP\Desktop\Campus_Network\resources\views\components\auth-card.blade.php ENDPATH**/ ?>