<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
</head>

<script src="/custom/js/jquery-1.9.1.js"></script>
<script src="/backoffice/html/js/Siviglia.js"></script>
<script>
    var ajaxEndPoint='/backoffice/html/endPoint.php';
</script>
<script src="/backoffice/html/js/Payment/PaymentEditor.js"></script>

<style type="text/css">
    .Window {background-color:white}
    .WindowContent {overflow:scroll}

    .order {background-color:#D4D0C8;margin-top:5px;border:1px solid #000044}
    .orderHeader {background-color:#CC3300;color:white}
    .orderHeader .f {float:left;
        padding-right: 5px;
        margin-left: 5px;
        font-size:16px;
        text-transform:capitalize;
        border-right: 1px solid white;}
    .paid_div {font-weight:bold;background-color:white;color:#CC3300}
    .firstname {font-size:19px}
    .lastname {font-size:19px}
    .Shipment div {float:left;margin-right:10px;font-size:12px}
    .SinglePayment div {float:left}
    .Detail {}

    .selButton {background-color:green;color:white}

    .shippedTo {font-size:12px}
    .totalHeader div {width:100px;text-align:center;border-bottom:1px solid black}
    .mP_date,.mP_description {font-size:12px}

    .mP_bruto_div,.mP_taxes_div,.mP_comision_div,.mP_comisionTaxes_div,.mP_refunds_div
    {width:100px;text-align:center;border-left:1px solid black;height:20px;font-size:14px}
    .MenuItem {float:left;padding:5px}
    table,td {font-size:18px;border:1px solid black}
    table .tableHeader {background-color:black;color:white}

</style>
<style type="text/css">

    .overlay {
        display: none; /* ensures it’s invisible until it’s called */
        position: fixed; /* makes the div go into a position that’s absolute to the browser viewing area */
        left: 1px; /* positions the div half way horizontally */
        top: 1px; /* positions the div half way vertically */
        padding: 5px;
        border: 2px solid black;
        background-color: #ffffff;
        width: 1300px;
        height: 800px;
        z-index: 100; /* makes the div the top layer, so it’ll lay on top of the other content */
    }
    #fade {
        display: none;  /* ensures it’s invisible until it’s called */
        position:fixed;  /* makes the div go into a position that’s absolute to the browser viewing area */
        left: 0%; /* makes the div span all the way across the viewing area */
        top: 0%; /* makes the div span all the way across the viewing area */
        background-color: black;
        -moz-opacity: 0.7; /* makes the div transparent, so you have a cool overlay effect */
        opacity: .70;
        filter: alpha(opacity=70);
        width: 100%;
        height: 100%;
        z-index: 90; /* makes the div the second most top layer, so it’ll lay on top of everything else EXCEPT for divs with a higher z-index (meaning the #overlay ruleset) */
    }

</style>
<script>
    var matchI;
    function checkSermepa()
    {
        if(matchI)
        {
            matchI.hide();
            matchI=null;
        }

        matchI=new Matcher(ajaxEndPoint+'?o=Payment&ds=unassignedModule&module=servired&source=servired','orders','id_order','Pedidos','module','id_payment','Servired');
        matchI.show();
    }
    function checkMRWCOD()
    {
        if(matchI)
        {
            matchI.hide();
            matchI=null;
        }

        matchI=new Matcher(ajaxEndPoint+'?o=Payment&ds=unassignedModule&module=cashondelivery&source=MRWCOD','orders','id_order','Pedidos','module','id_payment','MRW COD');
        matchI.show();
    }
    function checkShipments()
    {
        if(matchI)
        {
            matchI.hide();
            matchI=null;
        }

        matchI=new Matcher(ajaxEndPoint+'?o=Payment&ds=unassignedShipments','orders','id_order','Pedidos','shipments','id_payment','Envios');
        matchI.show();
    }
    function checkPaypal()
    {
        if(matchI)
        {
            matchI.hide();
            matchI=null;
        }

        matchI=new Matcher(ajaxEndPoint+'?o=Payment&ds=unassignedModule&module=paypal&source=paypal','orders','id_order','Pedidos','module','id_payment','Paypal');
        matchI.show();
    }
    function checkASM()
    {
        if(matchI)
        {
            matchI.hide();
            matchI=null;
        }

        matchI=new Matcher(ajaxEndPoint+'?o=Payment&ds=unassignedModule&module=cashondelivery&source=ASM','orders','id_order','Pedidos','module','id_payment','ASM COD');
        matchI.show();
    }
    function checkRefundShipments()
    {

        if(matchI)
        {
            matchI.hide();
            matchI=null;
        }

        matchI=new Matcher(ajaxEndPoint+'?o=Payment&ds=unassignedRefundShipments','orders','id_order','Devoluciones','shipments','id_payment','Recibidos','refunds');
        matchI.show();
    }
    function checkBags()
    {
        showIt();
    }
</script>
<body>
<div id="fade"></div>
<div class="menu">
    <div class="MenuItem"><a href="ManualPaymentFix.php">Pedidos</a></a></div>
    <div class="MenuItem"><a href="checks.php">Checks</a></div>
    <div class="MenuItem"><a href="javascript:void(0)" onclick="checkShipments()">Match Shipments</a></div>
    <div class="MenuItem"><a href="javascript:void(0)" onclick="showIt2()">Match Refund Shipments</a></div>
    <div class="MenuItem"><a href="javascript:void(0)" onclick="showIt3()">Match Sermepa</a></div>
    <div class="MenuItem"><a href="javascript:void(0)" onclick="checkMRWCOD()">Match MRW COD</a></div>
    <div class="MenuItem"><a href="javascript:void(0)" onclick="checkPaypal()">Match Paypal</a></div>
    <div class="MenuItem"><a href="javascript:void(0)" onclick="checkASM()">Match ASM COD</a></div>
    <div class="MenuItem"><a href="javascript:void(0)" onclick="checkBags()">Check bags</a></div>
</div>
<div style="clear:both"></div>