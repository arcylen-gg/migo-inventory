<!DOCTYPE html>
<html>
	<head>
		<title>My Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
		<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
	</head>
	<body>
		<div data-role="page">
			<div data-role="header">
				<h1>My Title</h1>
				<a href="#nav-panel" data-icon="bars" data-iconpos="notext">Menu</a>
				<a href="#add-form" data-icon="plus" data-iconpos="notext">Add</a>
			</div>
			<div data-role="content">
				<ul data-role="listview" data-inset="true" data-filter="true">
					<li><a href="#">Acura</a></li>
					<li><a href="#">Audi</a></li>
					<li><a href="#">BMW</a></li>
					<li><a href="#">Cadillac</a></li>
					<li><a href="#">Ferrari</a></li>
				</ul>
			</div>
		</div>
		<div data-role="footer" data-id="foo1" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li><a href="a.html">Info</a></li>
					<li><a href="b.html">Friends</a></li>
					<li><a href="c.html">Albums</a></li>
					<li><a href="d.html">Emails</a></li>
				</ul>
			</div>
		</div>
		<div data-role="panel" data-position="left" data-position-fixed="false" data-display="reveal" id="nav-panel" data-theme="a">
			<ul data-role="listview" data-theme="a" data-divider-theme="a" class="nav-search">
				<li data-icon="delete" style="background-color:#111;">
					<a href="/members" data-rel="close">Close menu</a>
				</li>
				<li data-filtertext="wai-aria voiceover accessibility screen reader">
					<a href="../about/accessibility.html">Accessibility</a>
				</li>
				<li data-filtertext="accordions collapsible set collapsible-set collapsed">
					<a href="../content/content-collapsible-set.html">Accordions</a>
				</li>
			</ul>
		</div>
	</body>
</html>