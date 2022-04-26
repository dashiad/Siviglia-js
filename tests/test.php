<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>test</title>

        <script src="http://statics.adtopy.com/node_modules/jquery/dist/jquery.js"></script>
<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->

    <script src="http://statics.adtopy.com/node_modules/jqwidgets-scripts/jqwidgets/jqx-all.js"></script>
    <script src="http://statics.adtopy.com/node_modules/jqwidgets-scripts/jqwidgets/globalization/globalize.js"></script>

    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/JqxWidgets.css">
    <link rel="stylesheet" type="text/css"
          href="http://statics.adtopy.com/packages/Siviglia/jQuery/css/jqx.adtopy-dev.css">

    <link rel="stylesheet" type="text/css" href="http://statics.adtopy.com/node_modules/jqwidgets-scripts/jqwidgets/styles/jqx.base.css">

    <script type="text/javascript">
      $(document).ready(function () {
        var source = [
          {
            label: "Item 1",
            expanded: true,
            items: [
              {label: "Item 1.1"},
              {
                label: "Item 1.2",
                selected: true
              }
            ]
          },
          {label: "Item 2"},
          {label: "Item 3"},
          {
            label: "Item 4",
            items: [
              {label: "Item 4.1"},
              {label: "Item 4.2"}
            ]
          },
          {label: "Item 5"},
          {label: "Item 6"},
          {label: "Item 7"}
        ];
        // Create jqxTree.
        $('#jqxTree').jqxTree({source: source, height: '300px', width: '300px'});
      });
    </script>
</head>

<body class='default'>
<div id='jqxTree'>
</div>
</body>
</html>
