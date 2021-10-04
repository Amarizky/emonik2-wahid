<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- Page header -->
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="" class="breadcrumb-item"><i class="<?php echo $icon; ?> mr-2"></i> <?php echo $menu; ?></a>
                <?php echo $sub_menu != "" ? '<a href="#" class="breadcrumb-item">' . $sub_menu . '</a>' : ''; ?>
                <?php echo $sub_title != "" ? '<span class="breadcrumb-item active">' . $sub_title . '</span>' : ''; ?>
            </div>

        </div>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <div class="row">
        
        <div class="col-xl-7">
            <div class="card card-body">
                <canvas id="dashboardChart1"></canvas>
                <div class="row">
                    <?php for ($i = 0; $i < count($chart1['bLabels']); $i++) : ?>
                        <?php $label = $chart1['bLabels'][$i]; ?>
                        <?php $link = $chart1['bLinks'][$i]; ?>
                        <div class="col-3 text-center my-2">
                            <a href="Produk/details/<?= $link; ?>" class="btn btn-primary w-75"><?= $label; ?></a>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-5">
            <div class="card card-body">
                <canvas id="dashboardChart2"></canvas>
                <div class="row">
                    <?php foreach ($chart2['bLabels'] as $label) : ?>
                        <div class="col text-center my-2">
                            <a href="Produk/detailproduksi/<?= $label; ?>" class="btn btn-primary w-75"><?= ucwords($label); ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
        </div>
        
    </div>
</div>

<script>
    const labels = [<?= '"' . implode('","', $chart1['labels']) . '"'; ?>];
    const data = {
        labels: labels,
        datasets: [{
            label: 'Data Bahan Baku Mitra',
            data: [<?= implode(',', $chart1['data']); ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ],
            borderWidth: 1
        }]
    };

    var myChart = new Chart(
        document.getElementById('dashboardChart1'), {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        }
    )


    const data2 = {
        labels: [<?= $chart2['labels']; ?>],
        datasets: [{
            label: 'Data Produksi',
            data: [<?= $chart2['data']; ?>],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    }

    var myChart2 = new Chart(
        document.getElementById('dashboardChart2'), {
            type: 'doughnut',
            data: data2,
            responsive: true
        }
    )
</script>
<!-- /content area -->