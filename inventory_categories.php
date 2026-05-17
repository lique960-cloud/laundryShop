<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Categories & Brands — HypeLaundry</title>
    <meta name="description" content="Manage Inventory Categories and Brands">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link href="assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-theme.css">
  </head>
  <body class="hold-transition skin-blue sidebar-mini modern-theme">
    <div class="wrapper">

      <header class="main-header">
        <a href="home.php" class="logo">
          <span class="logo-mini"><b>H</b>L</span>
          <span class="logo-lg">📦 <b>Hype</b>Laundry</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
        </nav>
      </header>

      <aside class="main-sidebar">
        <section class="sidebar">
          <ul class="sidebar-menu">
          <?php include_once('navigation.php'); ?>
          </ul>
        </section>
      </aside>

      <div class="content-wrapper">
        <section class="content-header">
          <div class="welcome-section">
            <div class="greeting">System Setup</div>
            <div class="page-title">Categories & Brands</div>
          </div>
        </section>

        <section class="content">
          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-list" style="margin-right:8px; color:#818cf8;"></i>Categories</h3>
                </div>
                <div class="box-body">
                  <form id="form-category" class="form-inline" style="margin-bottom: 20px;">
                    <input type="text" class="form-control" id="cat_name" name="category_name" placeholder="Category Name" required>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                  </form>
                  <div id="table-category"></div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-tags" style="margin-right:8px; color:#818cf8;"></i>Brands</h3>
                </div>
                <div class="box-body">
                  <form id="form-brand" class="form-inline" style="margin-bottom: 20px;">
                    <input type="text" class="form-control" id="brand_name_input" name="brand_name" placeholder="Brand Name" required>
                    <button type="submit" class="btn btn-primary">Add Brand</button>
                  </form>
                  <div id="table-brand"></div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 4.0
        </div>
        <strong>Copyright &copy; 2026 <a href="#">HypeLaundry</a>.</strong> Sales & Inventory Management System.
      </footer>
    </div>

    <?php include_once('modal/msg.php'); ?>
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('script.php'); ?>
    
    <script>
        $(document).ready(function() {
            load_categories();
            load_brands();
        });

        function load_categories(){
            $.ajax({
                url: 'data/all_categories.php',
                type: 'post',
                success: function (data) {
                    $('#table-category').html(data);
                }
            });
        }

        function load_brands(){
            $.ajax({
                url: 'data/all_brands.php',
                type: 'post',
                success: function (data) {
                    $('#table-brand').html(data);
                }
            });
        }

        $('#form-category').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'data/insert_category.php',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(data){
                    if(data.valid){
                        $('#cat_name').val('');
                        load_categories();
                    }
                }
            });
        });

        $('#form-brand').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'data/insert_brand.php',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(data){
                    if(data.valid){
                        $('#brand_name_input').val('');
                        load_brands();
                    }
                }
            });
        });

        function deleteCategory(id){
            if(confirm('Are you sure you want to delete this category?')){
                $.ajax({
                    url: 'data/delete_category.php',
                    type: 'post',
                    data: {id:id},
                    success: function(){ load_categories(); }
                });
            }
        }

        function deleteBrand(id){
            if(confirm('Are you sure you want to delete this brand?')){
                $.ajax({
                    url: 'data/delete_brand.php',
                    type: 'post',
                    data: {id:id},
                    success: function(){ load_brands(); }
                });
            }
        }
    </script>
  </body>
</html>
