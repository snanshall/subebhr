<!-- START row-->
<?php
$client_payments = 0;
$client_outstanding = 0;
$total_estimate = 0;
$started = 0;
$in_progress = 0;
$cancel = 0;
$completed = 0;

$tickets_answered = 0;
$tickets_closed = 0;
$tickets_open = 0;
$tickets_in_progress = 0;

if (!empty($all_client_info)):foreach ($all_client_info as $v_client):

    $project_tickets = $this->db->get('tbl_tickets')->result();
    if (!empty($project_tickets)) {
        foreach ($project_tickets as $v_tickets) {
            $profile_info = $this->db->where(array('user_id' => $v_tickets->reporter))->get('tbl_account_details')->row();
            if (!empty($profile_info)) {
                if ($profile_info->company == $v_client->client_id) {
                    if ($v_tickets->status == 'answered') {
                        $tickets_answered += count($v_tickets->status);
                    }
                    if ($v_tickets->status == 'closed') {
                        $tickets_closed += count($v_tickets->status);
                    }
                    if ($v_tickets->status == 'open') {
                        $tickets_open += count($v_tickets->status);
                    }
                    if ($v_tickets->status == 'in_progress') {
                        $tickets_in_progress += count($v_tickets->status);
                    }
                }
            }
        }

    }

endforeach;
endif;

?>

<div class="row">
    <div class="col-md-12">
        <div id="panelChart5" class="panel panel-custom">
            <div class="panel-heading">
                <div class="panel-title"><?= lang('client') . ' ' . lang('tickets') . ' ' . lang('report') ?></div>
            </div>
            <div class="panel-body">
                <div class="tickets_chart-pie flot-chart"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/plugins/Chart/Chart.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.tooltip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.resize.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.pie.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.time.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.categories.js"></script>
<script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.spline.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        // Polar chart
        // -----------------------------------
        var polarData = [
            {
                value: <?= $client_payments + $client_outstanding?>,
                color: '#27c24c',
                highlight: '#27c24c',
                label: '<?= lang('invoice_amount')?>'
            },
            {
                value: <?= $client_payments?>,
                color: '#23b7e5',
                highlight: '#23b7e5',
                label: '<?= lang('paid_amount')?>'
            },
            {
                value: <?= $client_outstanding?>,
                color: '#ff902b',
                highlight: '#ff902b',
                label: '<?= lang('due_amount')?>'
            },
            {
                value: <?= $total_estimate?>,
                color: '#f05050',
                highlight: '#f05050',
                label: '<?= lang('estimates') . ' ' . lang('amount')?>'
            },
        ];

        var polarOptions = {
            scaleShowLabelBackdrop: true,
            scaleBackdropColor: 'rgba(255,255,255,0.75)',
            scaleBeginAtZero: true,
            scaleBackdropPaddingY: 1,
            scaleBackdropPaddingX: 1,
            scaleShowLine: true,
            segmentShowStroke: true,
            segmentStrokeColor: '#fff',
            segmentStrokeWidth: 2,
            animationSteps: 100,
            animationEasing: 'easeOutBounce',
            animateRotate: true,
            animateScale: false,
            responsive: true
        };

        var polarctx = document.getElementById("chartjs-polarchart").getContext("2d");
        var polarChart = new Chart(polarctx).PolarArea(polarData, polarOptions);

        // CHART PIE
        // -----------------------------------
        (function (window, document, $, undefined) {

            $(function () {

                var data = [{
                    "label": "<?= lang('paid_amount')?>",
                    "color": "#23b7e5",
                    "data": <?= $client_payments?>
                }, {
                    "label": "<?= lang('due_amount')?>",
                    "color": "#ff902b",
                    "data": <?= $client_outstanding?>
                }, {
                    "label": "<?= lang('invoice_amount')?>",
                    "color": "#27c24c",
                    "data": <?= $client_payments + $client_outstanding?>
                }, {
                    "label": "<?= lang('estimates') . ' ' . lang('amount')?>",
                    "color": "#f05050",
                    "data": <?= $total_estimate?>
                }];

                var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0,
                            label: {
                                show: true,
                                radius: 0.8,
                                formatter: function (label, series) {
                                    return '<div class="flot-pie-label">' +
                                            //label + ' : ' +
                                        Math.round(series.percent) +
                                        '%</div>';
                                },
                                background: {
                                    opacity: 0.8,
                                    color: '#222'
                                }
                            }
                        }
                    }
                };

                var chart = $('.chart-pie');
                if (chart.length)
                    $.plot(chart, data, options);

            });

        })(window, document, window.jQuery);

        // CHART PIE
        // -----------------------------------
        (function (window, document, $, undefined) {

            $(function () {
                var data = [{
                    "label": "<?= lang('answered')?>",
                    "color": "#ff902b",
                    "data": <?= $tickets_answered?>
                }, {
                    "label": "<?= lang('in_progress')?>",
                    "color": "#5d9cec",
                    "data": <?= $tickets_in_progress?>
                }, {
                    "label": "<?= lang('closed')?>",
                    "color": "#23b7e5",
                    "data": <?= $tickets_closed?>
                }, {
                    "label": "<?= lang('open')?>",
                    "color": "#7266ba",
                    "data": <?= $tickets_open?>
                }];

                var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0,
                            label: {
                                show: true,
                                radius: 0.8,
                                formatter: function (label, series) {
                                    return '<div class="flot-pie-label">' +
                                            //label + ' : ' +
                                        Math.round(series.percent) +
                                        '%</div>';
                                },
                                background: {
                                    opacity: 0.8,
                                    color: '#222'
                                }
                            }
                        }
                    }
                };

                var chart = $('.tickets_chart-pie');
                if (chart.length)
                    $.plot(chart, data, options);

            });

        })(window, document, window.jQuery);
        // CHART BAR STACKED
        // -----------------------------------

    });

</script>