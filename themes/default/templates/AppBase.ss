<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<% base_tag %>
		$MetaTags(true)
		<% include OG %>
		<meta name="viewport" content="width=device-width">
		$getCSS
		<% include GA %>
	</head>
	<body class="app" id="$URLSegment">
		<header id="header"></header>

		<main id="main">
			$Layout
		</main>

		<footer></footer>
	</body>
</html>
