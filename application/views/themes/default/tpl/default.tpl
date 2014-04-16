<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{meta->description}">
    <meta name="keywords" content="{meta->keywords}">
    <meta name="author" content="{meta->author}">

    <title>{meta->site_title}</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <!-- theme -->
    <link href="{T_Folder}/css/plan.min.css" rel="stylesheet">
    <link href="{T_Folder}/css/bootstrap.belizehole.min.css" rel="stylesheet">
    <link href="{T_Folder}/css/plan.belizehole.min.css" rel="stylesheet">
    <link href="{T_Folder}/css/bootstrap-select.min.css" rel="stylesheet">

    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="{T_Folder}/css/bootstrap-select.min.css">

    <!-- Bootstrap Tags CSS -->
    <link rel="stylesheet" href="{T_Folder}/css/bootstrap-tagsinput.css">

    <!-- JQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <!-- Bootstrap Select JS -->
    <script src="{T_Folder}/js/bootstrap-select.min.js"></script>

    <!-- Theme UI elements JS -->
    <script src="{T_Folder}/js/plan.ui.js"></script>

    <!-- Bootstrap Tags JS -->
    <script src="{T_Folder}/js/bootstrap-tagsinput.min.js"></script>

    <!-- Custom JS -->
    <script src="{T_Folder}/js/application.js"></script>

    <!-- HTML5 WYSIWYG Editor -->
    <script src="{T_Folder}/js/bootstrap-wysiwyg.js"></script>
    <script src="{T_Folder}/js/jquery.hotkeys.js"></script>
    <script src="{T_Folder}/js/google-code-prettify/prettify.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{T_Folder}/css/custom.css">

    <!-- Font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,700,300italic' rel='stylesheet' type='text/css'>
</head>

<body>
    {navigation}
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                {breadcrumb}
                {content}
            </div>
            <div class="col-md-3">
                {right_sidebar}
            </div>
        </div> <!-- row -->
    </div> <!-- container -->
    {footer}
</body>

</html>