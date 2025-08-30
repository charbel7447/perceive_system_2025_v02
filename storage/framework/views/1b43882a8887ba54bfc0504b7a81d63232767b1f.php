<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.ico')); ?>" type="image/x-icon">
    <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(config('app.name')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', config('app.name')); ?>">
    <meta name="author" content="<?php echo $__env->yieldContent('meta_author', config('app.name')); ?>">

    <?php echo $__env->yieldContent('meta'); ?>
    <?php echo $__env->yieldPushContent('before-styles'); ?>

    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/font-awesome/css/font-awesome.min.css')); ?>">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <?php echo $__env->yieldPushContent('after-styles'); ?>
    <?php echo $__env->yieldContent('page-styles'); ?>

    <!-- Custom Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/mooli.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(mix('css/app.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(url('/')); ?>/css/bootstrap.css">

    <style>
        #main-content {
            width: 100% !important;
        }
        @media  screen and (max-width: 1200px) {
            .sidebar {left:0 !important;}
        }
    </style>
</head>

<body data-theme="light">
    <div id="body" class="theme-cyanx">
        <div id="wrapper">
            <div id="main-contentx">
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('before-scripts'); ?>

    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap and vendor bundles -->
    <script src="<?php echo e(asset('assets/bundles/libscripts.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/bundles/vendorscripts.bundle.js')); ?>"></script>

    <!-- Project Main Scripts -->
    <script src="<?php echo e(asset('assets/bundles/mainscripts.bundle.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('after-scripts'); ?>
    <?php echo $__env->yieldContent('vendor-script'); ?>
    <?php echo $__env->yieldContent('page-script'); ?>

    <!-- Optional: Safe DOMContentLoaded wrapper -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Example: initialize a DataTable if it exists
            if ($('#myTable').length) {
                $('#myTable').DataTable();
            }

            // Your custom event bindings go here
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/layout/masterCustom2.blade.php ENDPATH**/ ?>