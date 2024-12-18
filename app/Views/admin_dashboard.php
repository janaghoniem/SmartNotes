<?php
//connect to database
require_once __DIR__ . '/../../app/Controllers/UserController.php';
require_once __DIR__ . '/../../app/includes/session.php';
include_once  __DIR__ . '/../../app/Models/UserActivity.php';
include_once  __DIR__ . '/../../app/includes/ChartsData.php';

//set current page to update sidebar status
$current_page = 'Admin dashboard';

$users = UserActivity::getTopActiveUsers();
$PieChartData = ChartsData::getTopFeatures();
$BarChartData = ChartsData::getDailyUsageHours();
$LineChartData = ChartsData::getPeakUsageTimes();
$BigDashboardChart = ChartsData::getMonthlyActiveAccounts();

?>

<!--

=========================================================
* Now UI Dashboard - v1.5.0
=========================================================

* Product Page: https://www.creative-tim.com/product/now-ui-dashboard
* Copyright 2019 Creative Tim (http://www.creative-tim.com)

* Designed by www.invisionapp.com Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../../public/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../public/assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Now UI Dashboard by Creative Tim
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
    name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../../public/assets/css/now-ui-dashboard.css" rel="stylesheet" />
  <link href="../../public/assets/css/demo.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../public/assets/css/user_style.css">
</head>

<body class="">
  <div class="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-panel" id="main-panel">
      <!-- Navbar -->
      <?php include '../includes/admin_navbar.php'; ?>
      <!-- End Navbar -->
      <div class="panel-header panel-header-lg">
        <canvas id="bigDashboardChart"></canvas>
      </div>
      <div class="content">
        <div class="row">
          <div class="col-lg-4">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Feature Usage</h5>
                <h4 class="card-title">Top Features Engagement</h4>
              </div>
              <div class="card-body">
                <div class="chart-area">
                  <canvas id="pieChartExample"></canvas>
                </div>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Peak User Activity</h5>
                <h4 class="card-title">Tracking Peak Usage Times</h4>
              </div>
              <div class="card-body">
                <div class="chart-area">
                  <canvas id="lineChartExampleWithNumbersAndGrid"></canvas>
                </div>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Daily User Engagement</h5>
                <h4 class="card-title">Total Usage Hours Per Day</h4>
              </div>
              <div class="card-body">
                <div class="chart-area">
                  <canvas id="barChartSimpleGradientsNumbers"></canvas>
                </div>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="now-ui-icons ui-2_time-alarm"></i> Last 7 days
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-category">User Insights</h5>
                <h4 class="card-title">Top 5 Active Users</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="dataTable">
                    <thead class=" text-primary">
                      <th>
                        Name
                      </th>
                      <th>
                        Country
                      </th>
                      <th>
                        Age Group
                      </th>
                      <th>
                        Usage Category
                      </th>
                      <th>
                        Most Used Feature
                      </th>
                    </thead>
                    <tbody>
                      <?php foreach ($users as $user): ?>
                        <tr>
                          <td><?php echo $user['first_name'] . " " . $user['last_name']; ?></td>
                          <td><?php echo $user['country']; ?></td>
                          <td><?php echo $user['age_group_option_id']; ?></td>
                          <td><?php echo $user['usage_option_id']; ?></td>
                          <td><?php echo UserActivity::getMostUsedFeatureType($user['id']); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../../public/assets/js/core/jquery.min.js"></script>
  <script src="../../public/assets/js/core/popper.min.js"></script>
  <script src="../../public/assets/js/core/bootstrap.min.js"></script>
  <script src="../../public/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Chart JS -->
  <script src="../../public/assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../../public/assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../public/assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script>
  <!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="../../public/assets/js/demo.js"></script>
  <script>
    $(document).ready(function () {
      // Javascript method's body can be found in assets/js/demos.js
      var barChartData = <?php echo json_encode($BarChartData); ?>;
      var pieChartData = <?php echo json_encode($PieChartData); ?>;
      var lineChartData = <?php echo json_encode($LineChartData); ?>;
      var bigDashboardChart = <?php echo json_encode($BigDashboardChart); ?>;

      demo.initDashboardPageCharts(pieChartData, barChartData, lineChartData, bigDashboardChart);
      demo.initDocChart();
    });
  </script>
  <script src="../../public/assets/js/searchAdmin.js"></script>
</body>

</html>