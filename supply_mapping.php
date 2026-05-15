<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Supply Mapping — HypeLaundry</title>
    <meta name="description" content="Map laundry services to supply usage">
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
          <span class="logo-lg">🧺 <b>Hype</b>Laundry</span>
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
            <div class="greeting">Automation Setup</div>
            <div class="page-title">Supply Usage Mapping</div>
          </div>
        </section>

        <section class="content">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-link" style="margin-right:8px; color:#818cf8;"></i>Service-to-Supply Mapping</h3>
            </div>
            <div class="box-body">
              <div class="alert alert-info">
                Define how much of each supply is consumed per 1 unit/kg of each laundry service.
              </div>
              <div class="action-bar">
                <button id="newMapping" type="button" class="btn btn-primary btn-sm">
                  <i class="fa fa-plus" style="margin-right:5px;"></i> Add Mapping
                </button>
              </div>
              <div id="table-mapping"></div>
            </div>
          </div>
        </section>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 3.0
        </div>
        <strong>Copyright &copy; 2026 <a href="#">HypeLaundry</a>.</strong> All rights reserved.
      </footer>
    </div>

    <!-- Modal Mapping -->
    <?php 
    require_once('database/Database.php');
    $db = new Database();
    $laundry_types = $db->getRows("SELECT * FROM laundry_type ORDER BY laun_type_desc ASC");
    $items = $db->getRows("SELECT * FROM inventory ORDER BY item_name ASC");
    ?>
    <div class="modal fade" id="modal-mapping">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Service Usage Mapping</h4>
                </div>
                <div class="modal-body">
                    <form id="form-mapping">
                        <div class="form-group">
                            <label>Laundry Service:</label>
                            <select class="form-control" name="laun_type_id" required>
                                <?php foreach($laundry_types as $lt): ?>
                                <option value="<?= $lt['laun_type_id']; ?>"><?= $lt['laun_type_desc']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Supply Item:</label>
                            <select class="form-control" name="item_id" required>
                                <?php foreach($items as $i): ?>
                                <option value="<?= $i['id']; ?>"><?= $i['item_name']; ?> (<?= $i['unit']; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity Used (per unit/kg):</label>
                            <input type="number" step="0.001" class="form-control" name="quantity_used" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Mapping</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('modal/msg.php'); ?>
    <?php include_once('script.php'); ?>

    <script>
        $(document).ready(function() {
            load_mappings();
        });

        function load_mappings(){
            $.ajax({
                url: 'data/all_mappings.php',
                type: 'post',
                success: function (data) {
                    $('#table-mapping').html(data);
                }
            });
        }

        $('#newMapping').click(function(){
            $('#modal-mapping').modal('show');
        });

        $('#form-mapping').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'data/insert_mapping.php',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(data){
                    if(data.valid){
                        $('#modal-mapping').modal('hide');
                        load_mappings();
                        $('#modal-msg').find('#msg-body').text('Mapping saved!');
                        $('#modal-msg').modal('show');
                    }
                }
            });
        });

        function deleteMapping(id){
            if(confirm('Delete this mapping?')){
                $.ajax({
                    url: 'data/delete_mapping.php',
                    type: 'post',
                    data: {id:id},
                    success: function(){ load_mappings(); }
                });
            }
        }
    </script>
  </body>
</html>
<?php $db->Disconnect(); ?>
