<?php

error_reporting(E_ALL & ~E_NOTICE);

require '../../graph_config.php';
$conn = new mysqli($my_host, $my_user, $my_password, 'dbs_graph');

//$conn = new mysqli("localhost", "root", "sasa", "dima");
date_default_timezone_set('America/New_York');
$curr_day = date("Y-m-d");
$prev_date = new DateTime();
$prev_date->sub(new DateInterval('P30D'));
$prev_day = $prev_date->format('Y-m-d');


$result = $conn->query("SELECT 
							DATE(`time`) AS `request_date`,
							COUNT('log_id') AS `request_cnt`
						FROM `logs`
						WHERE `time` > '{$prev_day} 00:00:00'
						GROUP BY `request_date`
						ORDER BY `request_date`");
$dates = array();
$date = $prev_day;
while (strtotime($date) <= strtotime($curr_day)) {
	$dates[$date] = 0;
	$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
}
while($rs = $result->fetch_array(MYSQLI_ASSOC)) $dates[$rs['request_date']] = $rs['request_cnt']; 
$top_dates = [];
foreach ($dates as $request_date => $request_cnt) {
	$top_dates[] = array('request_date' => $request_date, 'request_cnt' => $request_cnt);
}


$result = $conn->query("SELECT 
							COUNT(a.`log_id`) AS `request_cnt`,
							b.`name` AS `user_name` 
						FROM `logs` a 
						JOIN `users` b ON a.`user_id` = b.`user_id` 
						WHERE `time` > '{$prev_day} 00:00:00'
						GROUP BY a.`user_id` 
						ORDER BY 
							`request_cnt` DESC, 
							`user_name` 
						");
$top_users = array();
while($rs = $result->fetch_array(MYSQLI_ASSOC)) $top_users[] = $rs;

$result = $conn->query("SELECT 
							COUNT(`log_id`) AS `request_cnt`, 
							`key` AS `request_key` 
						FROM `logs` 
						WHERE `time` > '{$prev_day} 00:00:00'
						  AND `error` = 0
						GROUP BY `request_key` 
						ORDER BY 
							`request_cnt` DESC, 
							`request_key` 
						LIMIT 100
						");
$top_requests = array();
while($rs = $result->fetch_array(MYSQLI_ASSOC)) $top_requests[] = $rs; 

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>RD Switchboard Usage Analytics</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,300italic,400italic,600italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="http://rd-switchboard.net/assets/core/css/portal.combine.css" media="screen">
<style>
	
.bar_chart text {
  fill: white;
  font: 10px sans-serif;
  text-anchor: end;
}

.bar_chart .label {
  fill: black;
  font: 10px sans-serif;
  text-anchor: end;
}
.bar {
  fill: steelblue;
}

.bar:hover {
  fill: brown;
}

.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
}
.align_center {
	text-align: center;
}
.navbar-brand {
	padding: 33px 15px 0 37px;
	position: relative;
	font-size: 16px;
}

.navbar-brand img {
	position: absolute;
	top: 24px;
	left: 0;
} 

.navbar-brand span {
	display: block;
	font-size: 25px;
}

@media (min-width: 768px) {
	.navbar-brand {
		padding: 33px 15px 0 52px;
	}

	.navbar-brand img {
		left: 15px;
	} 
}

@media (min-width: 600px) {
	.navbar-brand {
		font-size: 25px;
	}
	.navbar-brand span {
		display: inline;
		font-size: 25px;
	}
}
</style>
</head>
<body>
<div class="navbar swatch-black" role="banner">
	<div class="container" style="z-index:10">
		<div class="navbar-header">
			<div>
				<a href="http://rd-switchboard.net/" class="navbar-brand">
					<img src="http://rd-switchboard.net/assets/core/images/header_logo.png" alt="" border="0" width="32" height="32" />
					<span>RD-Switchboard</span> Browser&nbsp;(BETA)
				</a>
			</div>
		</div>
		<nav class="collapse navbar-collapse main-navbar" role="navigation">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="http://rd-switchboard.net/page/about">About</a></li>
			</ul>
		</nav>
	</div>
</div>
<article ng-controller="viewController" class="ng-scope">	
	<section style="z-index:1" class="section swatch-gray">
		<div class="container">
			<div class="row element-short-top">
				<div class="col-md-12 view-content">
					<div class="panel panel-primary swatch-white panel-content">
						<div class="panel-body">
							<div class="container-fluid">
								<div class="row">
									<h1 style="line-height:1.1em" class="hairline bordered-normal">Usage Analytics</h1>
								</div>
							</div>
						</div>
					</div>
					<div class="panel swatch-white">
						<div class="panel-heading">
					        <h3 class="panel-title">Requests per Day</h3>
					    </div>
						<div class="panel-body">
							<div class="container-fluid">
								<div class="row align_center">
									<svg class="top_calls_chart bar_chart"></svg>
								</div>
							</div>
						</div>
					</div>
					<div class="panel swatch-white">
						<div class="panel-heading">
					        <h3 class="panel-title">Requests per User</h3>
					    </div>
						<div class="panel-body">
							<div class="container-fluid">
								<div class="row align_center">
									<svg class="top_users_chart bar_chart"></svg>
								</div>
							</div>
						</div>
					</div>
					<div class="panel swatch-white">
						<div class="panel-heading">
					        <h3 class="panel-title">Top 100 Requests</h3>
					    </div>
						<div class="panel-body">
							<div class="container-fluid">
								<div class="row align_center">
									<svg class="top_request_chart bar_chart"></svg>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</article>
<footer id="footer" role="contentinfo">
	<section class="section swatch-black">
		<div class="container">
			<div class="row element-normal-top element-normal-bottom">
				<div class="col-md-10">
					<p>
RD-Switchboard is a new collaborative project by ANDS, CERN (European Organization for Nuclear Research), 
Dryad and number of other international partners in the Research Data Alliance. This platform enables finding 
connections between research datasets across national and international data registries.
					</p>
				</div>
				<div class="col-md-2">
					<a href="http://www.ands.org.au/" class="footer_logo"><img src="http://rd-switchboard.net/assets/core/images/footer_logo_rev.png" alt="" style="height:75px;"/></a>    
				</div>
			</div>
		</div>
    </section>
</footer>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>
var chartHeight     = 330,
    barWidth        = 20,
    spaceForLabels   = 70;

var top_dates = [];
<?php if ($top_dates.length) echo "	top_dates = " . json_encode($top_dates) . ";" ?>;

var chartWidth = top_dates.length * barWidth + 2;
var barHeight = d3.max(top_dates, function (d) { return parseInt(d.request_cnt); });

var y = d3.scale.linear()
    .domain([0, barHeight])
    .range([0, chartHeight]);
var x = d3.scale.linear()
    .range([chartWidth + 4, 0]);
var xAxis = d3.svg.axis()
    .scale(x)
    .tickFormat('')
    .tickSize(0)
    .orient("bottom");
var chart = d3.select(".top_calls_chart")
    .attr("width", chartWidth)
    .attr("height", chartHeight + spaceForLabels);
var bar = chart.selectAll("g")
    .data(top_dates)
    .enter().append("g")
    .attr("x", function(d, i) { return barWidth * i; })
    .attr("width", barWidth)
    .attr("y", function(d) { return chartHeight - d.request_cnt * chartHeight / barHeight; })
    .attr("height", function(d) { return (d.request_cnt * chartHeight / barHeight); });

bar.append("rect")
    .attr("class", "bar")
    .attr("x", function(d, i) { return barWidth * i; })
    .attr("y", function(d) { return chartHeight - d.request_cnt * chartHeight / barHeight; })
    .attr("width", barWidth -1)
    .attr("height", function(d) { return (d.request_cnt * chartHeight / barHeight); });

bar.append("text")
    .attr("fill", "white")
    .attr("dy", ".35em")
    .attr("transform", function(d, i) { return "translate(" + (barWidth * i + 10) + "," + (chartHeight - d.request_cnt * chartHeight / barHeight + 5) + "), rotate(-90)" ; })
    .text(function(d) { return d.request_cnt; });
bar.append("text")
    .attr("dy", ".30em")
    .attr("class", "label")
    .attr("transform", function(d, i) { return "translate(" + (barWidth * i + 10) + "," + (chartHeight + 5) + "), rotate(-90)" ; })
    .text(function(d){ return d.request_date; }); 
chart.append("rect")
      .attr("class", "x axis")
      .attr("x", 0)
      .attr("y", chartHeight)
      .attr("width", chartWidth)
      .attr("height", 1)

var chartWidth       = 600,
    barHeight        = 20,
    spaceForLabels   = 300;

var top_requests = [];
<?php if ($top_requests.length) echo "	top_requests = " . json_encode($top_requests) . ";" ?>;
var chartHeight = top_requests.length * barHeight + 2;
var x = d3.scale.linear()
    .domain([0, d3.max(top_requests, function (d) { return parseInt(d.request_cnt); })])
    .range([0, chartWidth]);
var y = d3.scale.linear()
    .range([chartHeight + 4, 0]);
var yAxis = d3.svg.axis()
    .scale(y)
    .tickFormat('')
    .tickSize(0)
    .orient("left");
var chart = d3.select(".top_request_chart")
    .attr("width", spaceForLabels + chartWidth)
    .attr("height", chartHeight);
var bar = chart.selectAll("g")
    .data(top_requests.map(function(d) { return d.request_cnt; }))
    .enter().append("g")
    .attr("transform", function(d, i) { return "translate(" + spaceForLabels + "," + (2 + (i * barHeight)) + ")" ; });
bar.append("rect")
    .attr("class", "bar")
    .attr("width", x)
    .attr("height", barHeight - 1);
bar.append("text")
    .attr("x", function(d) { return x(d) - 5; })
    .attr("y", barHeight / 2)
    .attr("fill", "white")
    .attr("dy", ".35em")
    .text(function(d) { return d; });
bar.append("text")
    .attr("x", function(d) { return -10 ; })
    .attr("y", barHeight / 2)
    .attr("dy", ".30em")
    .attr("class", "label")
    .text(function(d,i){ return top_requests[i].request_key; });
chart.append("g")
      .attr("class", "y axis")
      .attr("transform", "translate(" + spaceForLabels + ", -2)")
      .call(yAxis);
      
var top_users = [];
<?php if (top_users.length) echo "	top_users = " . json_encode($top_users) . ";" ?>;
var chartHeight = top_users.length * barHeight + 2;
var x = d3.scale.linear()
    .domain([0, d3.max(top_users, function (d) { return parseInt(d.request_cnt); })])
    .range([0, chartWidth]);
var y = d3.scale.linear()
    .range([chartHeight + 4, 0]);
var yAxis = d3.svg.axis()
    .scale(y)
    .tickFormat('')
    .tickSize(0)
    .orient("left");
var chart = d3.select(".top_users_chart")
    .attr("width", spaceForLabels + chartWidth)
    .attr("height", chartHeight);
var bar = chart.selectAll("g")
    .data(top_users.map(function(d) { return d.request_cnt; }))
    .enter().append("g")
    .attr("transform", function(d, i) { return "translate(" + spaceForLabels + "," + (2 + (i * barHeight)) + ")" ; });
bar.append("rect")
    .attr("class", "bar")
    .attr("width", x)
    .attr("height", barHeight - 1);
bar.append("text")
    .attr("x", function(d) { return x(d) - 5; })
    .attr("y", barHeight / 2)
    .attr("fill", "white")
    .attr("dy", ".35em")
    .text(function(d) { return d; });
bar.append("text")
    .attr("x", function(d) { return -10 ; })
    .attr("y", barHeight / 2)
    .attr("dy", ".30em")
    .attr("class", "label")
    .text(function(d,i){ return top_users[i].user_name; });
chart.append("g")
      .attr("class", "y axis")
      .attr("transform", "translate(" + spaceForLabels + ", -2)")
      .call(yAxis);
 
</script>
</body>
</html>