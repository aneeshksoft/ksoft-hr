<div class="row clearfix">
<div class="col-lg-4">
      <div class="card">
        <div class="card-body ">
          <div class="blog-card">
            <div>
              <h3 class="mb-3 mt-2">Welcome back <span class="empname"><?= htmlspecialchars($employee['name'], ENT_QUOTES, 'UTF-8') ?>!</span></h3>
              <p>Welcome to the KSoft family! We're excited to have you on board and look forward to achieving great things together.</p>
            </div>
            <div class="card-image">
              <img src="assets/common/dashboard-image-1.png" alt="image">
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php if (is_admin()) { ?>
    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <div class="row mt-4">

            <div class="col-md-6 mb-5">
              <h5 class="card-title ">Employee Details</h5>
              <div class="row mt-4">
                <a href="<?= base_url() ?>employee/view_employees" class="text-decoration-none">
                  <div class="col-md-12">
                    <div class="vstack gap-9 mt-2">
                      <div class="hstack align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center round-48 rounded" style="background-color: #DDDBFF;">
                          <i class="fa fa-users font-25"></i>
                        </div>
                        <div>
                          <h6 class="mb-0 text-nowrap" style="color: #22af46;">Total Employees</h6>
                          <span style="color:#98a4ae;"><?= $emp_count ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
                <a href="<?= base_url() ?>leave-report" class="text-decoration-none">
                  <div class="col-md-12">
                    <div class="vstack gap-9 mt-2">
                      <div class="hstack align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center round-48 rounded" style="background-color: #ffccdb;">
                          <i class="fa fa-users font-25"></i>
                        </div>
                        <div>
                          <h6 class="mb-0 text-nowrap" style="color: #de4848;">On Leave</h6>
                          <span style="color:#98a4ae;"><?= $leave ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
                <a href="<?= base_url() ?>leave-application-list" class="text-decoration-none">
                  <div class="col-md-12">
                    <div class="vstack gap-9 mt-2">
                      <div class="hstack align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center round-48 rounded" style="background-color: #a6f7f5;">
                          <i class="fa  fa-clock-o font-25"></i>
                        </div>
                        <div>
                          <h6 class="mb-0 text-nowrap" style="color: #ffc107;">Pending Leave Approvals</h6>
                          <span style="color:#98a4ae;"><?= $pending_approvals ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>

              </div>

            </div>

            <div class="col-md-6">
              <h5 class="card-title text-center">Leave Applications</h5>
              <div class="row mt-4">
                <div class="col-md-12">
                  <a href="<?= base_url() ?>leave-application-list">
                    <div class="text-center mt-sm-n7">
                      <div id="leave-approval-chart" style="max-height: 250px;"></div> <!-- Chart Container -->
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if (!is_admin()) { ?>
    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <div class="row mt-4">
            <div class="col-md-6 mb-5">

              <h5 class="card-title ">Available Leaves</h5>
              <div class="row mt-4">
                <?php foreach ($available_leaves as $leave): ?>
                  <a href="<?= base_url() ?>leave-application" class="text-decoration-none">
                    <div class="col-md-12">
                      <div class="vstack gap-9 mt-2">
                        <div class="hstack align-items-center gap-3">
                          <div class="d-flex align-items-center justify-content-center round-48 rounded" style="background-color: <?= $leave['bgcolor'] ?>;">
                            <i class="fa fa-calendar font-25"></i>
                          </div>
                          <div>
                            <h6 class="mb-0 text-nowrap" style="color: <?= $leave['color'] ?>;"><?= $leave['type'] ?> Available</h6>
                            <span style="color:#98a4ae;"><?= $leave['balance'] ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                <?php endforeach; ?>
              </div>
              <?php if (is_rm()) { ?>
                <div class="row ">
                  <a href="<?= base_url() ?>leave-application-list" class="text-decoration-none">
                    <div class="col-md-12">
                      <div class="vstack gap-9 mt-2">
                        <div class="hstack align-items-center gap-3">
                          <div class="d-flex align-items-center justify-content-center round-48 rounded" style="background-color: #80008085;">
                          <i class="fa  fa-clock-o font-25"></i>
                          </div>
                          <div>
                            <h6 class="mb-0 text-nowrap" style="color:#ffc107;">Pending Leave Approvals</h6>
                            <span style="color:#98a4ae;"><?= $pending_approvals ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              <?php } ?>
            </div>

            <div class="col-md-6">
              <h5 class="card-title text-center">Leave Applications</h5>
              <div class="row mt-4">
                <div class="col-md-12">
                  <a href="<?= base_url() ?>leave-application-list">
                    <div class="text-center mt-sm-n7">
                      <div id="leave-approval-chart" style="max-height: 250px;"></div> <!-- Chart Container -->
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
  <div class="col-lg-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center">Leave Summary</h5>
          <div class="row mt-4">
            <div class="col-md-12">
              <a href="<?= base_url() ?>leave-application-list">
                <div class="text-center mt-sm-n7">
                  <div id="leave-taken-chart"></div> <!-- Chart Container -->
                  <div id="chart-legend" style="text-align: center; margin-top: 20px;"></div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<style>
  .card-title {
    font-size: 18px !important;
    margin-bottom: 8px !important;
    color: #29343d !important;
  }

  .fw-semibold {
    font-weight: 600 !important;
  }

  .lh-base {
    line-height: 1.5 !important;
  }

  .gap-9 {
    gap: 20px !important;
  }

  .vstack {
    display: flex;
    flex: 1 1 auto;
    flex-direction: column;
    align-self: stretch;
  }

  .hstack {
    display: flex;
    flex-direction: row;
    align-items: center;
    align-self: stretch;
    gap: 20px;
  }

  .round-48 {
    width: 48px;
    height: 48px;
  }

  .bg-primary-subtle {
    background-color: #DDDBFF !important;
  }

  .flex-shrink-0 {
    flex-shrink: 0 !important;
  }

  .text-nowrap {
    white-space: nowrap !important;
  }

  .bg-danger-subtle {
    background-color: #ffccdb !important;
  }

  .bg-secondary-subtle {
    background-color: #a6f7f5 !important;
  }

  .empname {
    color: #007bff;
    display: inline-block;
    max-width: 145px;
    /* Adjust to fit 10 characters based on font size */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: bottom;
    /* Align with surrounding text */
  }

  .card-image {
    position: relative !important;
    right: 22px !important;
    top: 20px !important;
    max-width: 100%;
  }
</style>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Dynamic data from PHP for Leave Applications chart
    const approved = <?= $approved ?? 0 ?>;
    const rejected = <?= $rejected ?? 0 ?>;
    const pending = <?= $pending ?? 0 ?>;

    // Check if there is data for Leave Applications
    const hasApplicationData = approved > 0 || rejected > 0 || pending > 0;

    // Leave Applications Chart
    const leaveApplicationsOptions = {
      series: hasApplicationData ? [approved, rejected, pending] : [], // Pass empty series if no data
      chart: {
        type: 'donut',
        height: 250,
      },
      plotOptions: {
        pie: {
          startAngle: -90,
          endAngle: 90,
          offsetY: 10,
          dataLabels: {
            offset: -10,
            minAngleToShowLabel: 10,
          }
        },
      },
      labels: hasApplicationData ? ['Approved', 'Rejected', 'Pending'] : [], // Show labels only if there is data
      colors: ['#28a745', '#dc3545', '#ffc107'],
      legend: hasApplicationData // Conditionally configure legend
        ?
        {
          position: 'bottom',
          offsetY: -40,
        } : {
          show: false
        }, // Hide legend if no data
      dataLabels: {
        enabled: hasApplicationData, // Show data labels only if there is data
        formatter: function(val, opts) {
          return opts.w.globals.series[opts.seriesIndex];
        },
        style: {
          fontSize: '14px',
        },
      },
      noData: {
        text: 'No Data Found',
        align: 'center',
        verticalAlign: 'middle',
        style: {
          fontSize: '16px',
          color: '#7d7d7d'
        }
      },
    };

    const leaveApplicationsChart = new ApexCharts(document.querySelector("#leave-approval-chart"), leaveApplicationsOptions);
    leaveApplicationsChart.render();
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // 1) Grab the original chart data from PHP
    const chartData = <?= json_encode($chart_data) ?>;

    // 2) Create a scaled series by multiplying each value by 10
    //    so smaller values show bigger arcs
    const scaledSeries = chartData.series.map(val => val * 10);

    // A helper to truncate decimal to 1 place
    function truncateOneDecimal(num) {
      // Example: 2.55 -> floor(25.5) / 10 -> 25 / 10 -> 2.5
      return Math.floor(num * 10) / 10;
    }

    // 3) Build the Radial Bar Chart options
    const leaveTakenOptions = {
      series: scaledSeries,
      chart: {
        height: 220,
        type: 'radialBar',
      },
      plotOptions: {
        radialBar: {
          track: {
            margin: 3
          },
          hollow: {
            size: "30%",
          },
          dataLabels: {
            name: {
              fontSize: '18px',
              formatter: function(val) {
                // If 'val' is "Total", preserve it for the total slice
                if (val === 'Total') return val;

                // Otherwise match the original label from chartData.labels
                const index = chartData.labels.indexOf(val);
                return (index !== -1) ? chartData.labels[index] : '';
              },
            },
            value: {
              fontSize: '18px',
              formatter: function(val, opts) {
                // 'val' here is the scaled value (since we used scaledSeries)
                // Convert back to the original, then truncate (not round)
                const originalValue = val / 10; // Unscale
                const truncated = truncateOneDecimal(originalValue);
                return truncated.toString();
              },
            },
            total: {
              show: true,
              label: 'Total',
              formatter: function(opts) {
                // The total is also scaled by 10, so revert it
                const totalScaled = opts.globals.seriesTotals.reduce((a, b) => a + b, 0);
                const totalOriginal = totalScaled / 10;
                // Truncate to 1 decimal place
                return truncateOneDecimal(totalOriginal);
              },
            },
          },
        },
      },
      labels: chartData.labels,
      colors: chartData.colors,
      legend: {
        position: 'bottom',
        offsetY: -40,
      },
      noData: {
        text: 'No Data Found',
        align: 'center',
        verticalAlign: 'middle',
        style: {
          fontSize: '16px',
          color: '#7d7d7d',
        },
      },
    };

    // 4) Render the chart
    const leaveTakenChart = new ApexCharts(
      document.querySelector("#leave-taken-chart"),
      leaveTakenOptions
    );
    leaveTakenChart.render();

    // 5) Build your custom legend
    const legendContainer = document.querySelector("#chart-legend");
    legendContainer.innerHTML = '';
    chartData.labels.forEach((label, index) => {
      const legendItem = document.createElement("div");
      legendItem.style.display = "inline-block";
      legendItem.style.margin = "0 10px";
      legendItem.style.textAlign = "center";
      legendItem.innerHTML = `
        <span 
          style="
            display: inline-block; 
            width: 12px; 
            height: 12px; 
            background-color: ${chartData.colors[index]}; 
            border-radius: 50%; 
            margin-right: 5px;
          "
        ></span>
        ${label}
      `;
      legendContainer.appendChild(legendItem);
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>