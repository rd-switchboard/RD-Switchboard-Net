<?php

error_reporting(E_ALL & ~E_NOTICE);

function protect($str, $max) {
    return substr(htmlentities(strip_tags(trim($str)), ENT_QUOTES), 0, $max);
}

function formElement($name, $type, $title, $placeholder, $error, $value, $posted) {
    echo "<tr><td class='ctitle'>{$title}&nbsp;";
    if ($error) 
	echo "<sup>*</sup>&nbsp;";
    echo "&nbsp;</td><td class='cctrl'><input type='{$type}' style='width: 100%' size='60' name='{$name}' placeholder='{$placeholder}' value='{$value}' />";
    if ($posted && $error && !$value) 
	echo "<div style='color: red'>{$error}</div>";
    echo "</td></tr>";
}

function formTextElement($name, $title, $placeholder, $error, $value, $posted) {
    echo "<tr><td class='ctitle' valign='top'>{$title}";
    if ($error) 
	echo "&nbsp;<sup>*</sup>&nbsp;&nbsp;";
    echo "</td><td class='cctrl'><textarea style='width: 100%' rows='5' name='{$name}' placeholder='{$placeholder}'>{$value}</textarea>";
    if ($posted && $error && !$value) 
	echo "<div style='color: red'>{$error}</div>";
    echo "</td></tr>";
}

$company = protect($_POST['company'], 100);
$website = protect($_POST['website'], 100);
$name = protect($_POST['name'], 100);
$email = protect($_POST['email'], 100);
$phone = protect($_POST['phone'], 100);
$reason = protect($_POST['reason'], 100);
$posted = protect($_POST['posted'], 1000);

if (($company && $website && $name && $email && $reason)) {
$to = 'dmitrij@kudriavcev.info,amir.aryani@ands.org.au';

// subject
$subject = 'Request for RD-Switchboard.net API registration';

// message
$message = "
<html>
<head><title>{$subject}</title></head>
<body>
  <h1>{$subject}</h1>
  <table><tbody>
    <tr><td>Organisation:&nbsp;&nbsp;</td><td>{$company}</td></tr>
    <tr><td>Website:&nbsp;&nbsp;</td><td>{$website}</td></tr>
    <tr><td>Contact Name:&nbsp;&nbsp;</td><td>{$name}</td></tr>
    <tr><td>Email:&nbsp;&nbsp; </td><td>{$email}</td></tr>
    <tr><td>Phone:&nbsp;&nbsp; </td><td>{$phone}</td></tr>
    <tr><td>Reason:&nbsp;&nbsp; </td><td>{$reason}</td></tr>
  </tbody></table>
</body>
</html>
";

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: RD-Switchboard Graph API <api@rd-switchboard.net>' . "\r\n";

@mail($to, $subject, $message, $headers);
$result = true;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Registration for RD-Switchboard.net Graph API</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,300italic,400italic,600italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="http://rd-switchboard.net/assets/core/css/portal.combine.css" media="screen">
<style>
table {
	width: 100%;
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

.panel-heading {
    font-weight: normal;
}

.container-fluid {
	padding: 0;
}

td.ctitle {
	width: 20%;
	font-size: 12px;
	white-space: normal;
	padding: 0 10px 7px 0;
}

td.cctrl {
	width: 80%;
	padding: 0 0 7px 0;
}

@media (min-width: 768px) {
	td.ctitle {
		width: 30%;
		font-size: 16px;
		padding: 0 20px 7px 0;
	}

	td.cctrl {
		width: 70%;
		padding: 0 0 7px 0;
	}
	.navbar-brand {
		padding: 33px 15px 0 52px;
	}

	.navbar-brand img {
		left: 15px;
	} 
}

@media (min-width: 992px) {
	table {
		width: 90%;
	}
}

@media (min-width: 1200px) {
	table {
		width: 75%;
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

input, textarea {
	padding: 3px 5px;
	display: block;
}

textarea {
	resize:vertical; 
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
					<div class="panel swatch-white">
<?php if ($result): ?>
						<div class="panel-heading">
					        <h3 class="panel-title">Thanks!</h3>
					    </div>
						<div class="panel-body">
							<div class="container-fluid">
								<p>Thank you for your registration. We will contact you shortly.</p>
							</div>
						</div>
<?php else: ?>
						<div class="panel-heading">
					        <h3 class="panel-title">Research Data Switchboard API: Registration Form</h3>
						<br/>
						<p>Please complete the following registration form. We will contact you shortly after receiving your registration form to provide you with the API key.</p>
					    </div>
						<div class="panel-body">
							<div class="container-fluid">
								<form action="" method="post">
									<input type="hidden" name="posted" value="1" />
								<table>
									<tbody>
<?php 
    formElement("company", "text", "Organisation Name", "Name of your organisation or university.", "Please enter name of your organisation or university.", $company, $posted);
    formElement("website", "text", "Organisation Website", "Web address for your organisation.", "Please enter web address for your organisation.", $website, $posted);
    formElement("name", "text", "Contact Name", "Your title and name.", "Please enter your name.", $name, $posted);
    formElement("email", "email", "Contact Email", "Your email address.", "Please enter your email address.", $email, $posted);
    formElement("phone", "tel", "Contact Phone", "Your contact number.", null, $phone, $posted);
    formTextElement("reason", "Purpose&nbsp;of the&nbsp;access to&nbsp;RD-Switchboard&nbsp;API", "What cool things are you going to do? Or just enter the name of your project.", "Please enter name of your project.", $reason, $posted);
?>
										<tr><td>&nbsp;</td><td><input type="submit" /></td></tr>
									</tbody>
								</table>
								</form>
							</div>
						</div>
<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</article>
</body>
</html>
