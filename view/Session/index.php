<head>
<head>
    <link rel="stylesheet" type="text/css" media="screen" href="/css/ui-lightness/jquery-ui-1.8.18.custom.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="/css/ui.jqgrid.css"/>
    <script src="https://www.google.com/jsapi" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
    <script src="/js/i18n/grid.locale-en.js" type="text/javascript"></script>
    <script src="/js/jquery.jqGrid.min.js" type="text/javascript"></script>
</head>
<body>

<table id="list2"></table>
<div id="pager2"></div>

<script type="text/javascript">
    jQuery("#list2").jqGrid({
        url:'/Session/getData/',
        datatype:"json",
        colNames:['Key id', 'user id', 'last ip', 'last login'],
        colModel:[
            {name:'key_id', index:'key_id', width:200, align:"center"},
            {name:'user_id', index:'user_id', width:90, align:"center"},
            {name:'last_ip', index:'last_ip', width:100, align:"center"},
            {name:'last_login', index:'last_login', width:80, align:"center"}
        ],
        rowNum:10,
        rowList:[10, 20, 30],
        pager:'#pager2',
        sortname:'key_id',
        viewrecords:true,
        sortorder:"desc",
        jsonReader:{
            repeatitems:false,
            id:"0"
        },
        width: 800,
        height: 'auto',
        caption:"JSON Example"
    });
    jQuery("#list2").jqGrid('navGrid', '#pager2', {edit:true, add:true, del:true});
</script>
</body>
</head>