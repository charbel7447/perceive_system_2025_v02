<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<link rel="icon" href="<?php echo e(asset('assets/images/favicon.ico')); ?>" type="image/x-icon"> <!-- Favicon-->
<title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(config('app.name')); ?></title>
<meta name="description" content="<?php echo $__env->yieldContent('meta_description', config('app.name')); ?>">
<meta name="author" content="<?php echo $__env->yieldContent('meta_author', config('app.name')); ?>">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

<?php echo $__env->yieldContent('meta'); ?>
<?php echo $__env->yieldPushContent('before-styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>">    
<link rel="stylesheet" href="<?php echo e(asset('assets/vendor/font-awesome/css/font-awesome.min.css')); ?>">    
<!-- <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/animate-css/vivify.min.css')); ?>"> -->

<?php echo $__env->yieldPushContent('after-styles'); ?>
<?php if(trim($__env->yieldContent('page-styles'))): ?>    
<?php echo $__env->yieldContent('page-styles'); ?>
<?php endif; ?>    

<!-- Custom Css -->
<link rel="stylesheet" href="<?php echo e(asset('assets/css/mooli.min.css')); ?>">
</head>

<body data-theme="light">

<div id="body" class="theme-cyan">
    <!-- Theme setting div -->
    <div id="wrapper">
 
    <link rel="stylesheet" type="text/css" href="<?php echo e(mix('css/app.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(url('/')); ?>/css/bootstrap.css">
    <!-- Top Navbar -->
 
    

    <style>
        #main-content {
            width: 100% !important;
        }
           body, html {
        font-family: Lato !important;
    }
    </style>
        <div id="main-contentx">
            <div class="container-fluid">          

                <?php echo $__env->yieldContent('content'); ?>

            </div>
        </div>        
    </div>

    
</div>


<!-- main jquery and bootstrap Scripts -->
<?php echo $__env->yieldPushContent('before-scripts'); ?>
<script src="<?php echo e(asset('assets/bundles/libscripts.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('assets/bundles/vendorscripts.bundle.js')); ?>"></script>

<style>
@media  screen and (max-width: 1200px) {
  .sidebar {left:0 !important};  
}

    </style>
<?php echo $__env->yieldPushContent('after-scripts'); ?>

<!-- vendor js file -->
<?php echo $__env->yieldContent('vendor-script'); ?>

<!-- project main Scripts js-->
<script src="<?php echo e(asset('assets/bundles/mainscripts.bundle.js')); ?>"></script>

<!-- page Scripts ja -->
<?php echo $__env->yieldContent('page-script'); ?>


</body>
</html>
<?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/layout/masterCustom.blade.php ENDPATH**/ ?>