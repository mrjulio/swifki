<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="/assets/style.css"/>
    <link rel="stylesheet" href="/assets/user.css"/>
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script src="/assets/jstree/_lib/jquery.cookie.js"></script>
    <script src="/assets/jstree/jquery.jstree.js"></script>
    <script src="/assets/script.js"></script>
</head>
<body>

<div id="container">
    <div id="sidebar">
        <input id="search" type="text" placeholder="search in files..." />
        <div id="menu">
            <? echo $menu ?>
        </div>
    </div>
    <div id="template">
        <p class="breadcrumbs"><? echo $breadcrumbs ?></p>
        <div id="toc"><span id="toggle-toc">Contents: (+/-)</span><br/><div id="table-of-contents"></div></div>
        <?php include $template ?>
    </div>
    <div class="clearfix"></div>
    <div id="footer"><?php include 'footer.php' ?></div>
</div>
</body>
</html>