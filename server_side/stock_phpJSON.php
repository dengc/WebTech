<!-- Author: Chufan Deng
Stock show -->

<!DOCTYPE HTML>
<html>
<head>
    <style>
        body,p{
            text-align: center;
            margin: 5px auto;
        }
        form{
            display: inline-block;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            width: 630px;
            height: 260px;
            border: #d9d9d9 solid 1px;
        }
        #title{
            font-style:italic;
            font-size: 3em;
        }
        #line{
            background-color: #d9d9d9;
            height: 1px;
            margin:0 8px 25px 8px;
        }
        #tickerText{
            text-align: left;
            margin-left: -72px;
            font-size: 1.6em;
        }
        #tickerInput{
            width: 230px;
        }
        #btns{
            margin: 10px auto 10px 30%;
        }
        #btnSearch{
            height: 26px;
            width: 100px;
            font-size: 1.1em;
            margin-right:10px
        }
        #btnClear{
            height: 26px;
            width: 90px;
            font-size: 1.1em;
        }
        #mandText{
            text-align: left;
            margin-left: 8px;
            font-size: 1.5em;
            font-style:italic;
        }
        table{
            display: inline-block;
            border: 1px solid #d8d8d8;
            border-collapse: collapse;
        }
        th{
            background-color: #f5f5f5;
            width: 295px;
            text-align: left;
            border: 1px solid #e0e0e0;
            padding: 5px;
        }
        td{
            background-color: #fbfbfb;
            width: 997px;
            text-align: center;
            border: 1px solid #e0e0e0;
            padding: 5px;
        }
        span{
            color: blue;
            cursor: pointer;
        }
        #newsContent{
            border: 1px solid #d8d8d8;
            background-color: #fbfbfb;
            height: auto;
            width: 1319px;
            display: none;
            text-align: left;
        }
        a{
            text-decoration: none;
            color: blue;
        }
        ul {
            list-style-type: none;
        }
        li{
            margin: auto -39px;
        }
        .pubTime{
            margin-left: 40px;
            color: black;
            cursor: default;
        }
        .liLine{
            background-color: #e0e0e0;
            display: inline-block;
            height: 1px;
            width: 1319px;
            margin: 12px -40px;
        }
    </style>
    <script src="https://code.highcharts.com/highcharts.src.js"></script>
</head>

<body>

<?php
$ticker = $_POST["ticker"];
$url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
$apiLink = "&apikey=OE0QXT6U0BKFHVV8";
?>
<form name="myForm" method="post" action="<?=$_SERVER['PHP_SELF'];?>">
    <p id="title">Stock Search</p>
    <p id="line"></p>
    <label for="tickerInput" id="tickerText">Enter Stock Ticker Symbol:*</label>
    <input type ="text" name ="ticker" id ="tickerInput" value="<?php echo $ticker;?>"/>

    <div id="btns">
        <input id="btnSearch" type ="submit"  name="Search" value="Search" onclick="searchValue();"/>
        <input id="btnClear" type ="button" name="Clear" value ="Clear" onclick="clearInput();" />
    </div>

    <p id="mandText">* - Mandatory fields</p>
</form>

<?php

if(isset($_POST['Search'])){
    if($ticker !== "") {
        $json = file_get_contents($url . $ticker . $apiLink);
        $obj = json_decode($json, true);
        showTable($obj, $ticker);

        $closeArray = dayClose($obj);
        $volumeArray = dayVolume($obj);
        $date = $obj['Meta Data']['3. Last Refreshed'];
        showPrice($closeArray, $volumeArray, $date, $ticker);
        $xmlURL = "https://seekingalpha.com/api/sa/combined/".$ticker.".xml";
        $xmlStr = simplexml_load_file($xmlURL);
        $xmlJSON = json_encode($xmlStr);
        $xmlArray = json_decode($xmlJSON,TRUE);
        showNEWs($xmlArray);
    }
}
function showNEWs($xmlArray){
    $html = "<div id=\"newsContent\"><ul>";
    $count = 0;
    for($i = 0; $i < 1000; $i++){
        $titleURL = $xmlArray["channel"]["item"][$i]["link"];
        if(strpos($titleURL, 'article') !== false){
            $count++;
            $pubTime = $xmlArray["channel"]["item"][$i]["pubDate"];
            $html .= "<li><a target='_blank' href='". $titleURL ."'>".$xmlArray["channel"]["item"][$i]["title"]."</a>";
            $html .="<span class='pubTime'>Publicated Time: " .substr( $pubTime, 0, -6 )."</span></li>";
            if($count < 5){
                $html .= "<div class='liLine'></div>";
            }else{
                break;
            }
        }        
    }
    $html .= "</ul></div>";
    echo $html;
}
function showPrice($closeArray, $volumeArray, $date, $ticker){
    echo '<script type="text/javascript">',

    'var closeArray = ',json_encode($closeArray),';',
    'closeArray = closeArray.map(Number);',
    'var volumeArray = ',json_encode($volumeArray),';',
    'volumeArray = volumeArray.map(Number);',
    'var date = ',json_encode($date), ';',
    'var res = date.substring(5,7) + "/" + date.substring(8,10) + "/" + date.substring(0,4);',

    'var title = "Stock Price (" + res + ")";',
    'var ticker = ',json_encode($ticker),';',

    'var inputDate = Date.now() - 24 * 3600000 * 100;',

    'Highcharts.chart(\'toGraph\', {
                    chart: {
                        borderColor: \'#d8d8d8\',
                        borderWidth: 1,
                        marginRight: 200
                    },
                    title: {
                        text: title
                    },
                    subtitle: {
                        text: \'<a href="https://www.alphavantage.co/" style="color: blue">Source: Alpha Vantag</a>\'
                    },
                    tooltip:{
                        valueDecimals: 2,
                        xDateFormat: \'%m/%d\',
                    },
                    xAxis: [{
                        type: \'datetime\',
                        labels: {
                            format: \'{value:%m/%d}\'
                        }
                    }],
                    yAxis: [{
                        title: {
                            text: \'Stock Price\'
                        },
                        min: Math.min( ...closeArray ) - 5,
                        tickInterval: 5
                    }, {
                        labels: {
                            format: \'{value}M\'
                
                        },
                        title: {
                            text: \'Volume\'
                        },
                        opposite: true,
                        tickInterval: 50
                    }],
                    legend: {
                        align: \'right\',
                        verticalAlign: \'top\',
                        layout: \'vertical\',
                        x: 10,
                        y: 270
                    },
                
                    series: [{
                        type: \'area\',
                        name: ticker,
                        color: \'#ff898c\',
                        pointInterval: 24 * 3600000,
                        lineColor: \'red\',
                        marker: {
                            enabled: false,
                            fillColor: \'red\'
                        },
                        pointStart: inputDate,
                        data: closeArray
                    }, {
                        type: \'column\',
                        yAxis: 1,
                        name: ticker + \' Volume\',
                        color: \'white\',
                        pointInterval: 24 * 3600000,
                        pointStart: inputDate,
                        data: volumeArray,
                        tooltip: {
                            valueSuffix: \'M\'
                        },
                    }]
                });',
    '</script>';
}

function showTable($obj, $ticker){
    $obj1 = $obj['Time Series (Daily)'];

    $html = "<div id=\"toTable\"><table><tr><th>Stock Ticker Symbol</th><td>";
    $html .= $ticker;
    $html .= "</td></tr>";

    $recentDay = getAttributeByIndex($obj1, 0);
    $html .= "<tr><th>Close</th><td>";
    $close = $recentDay['4. close'];
    $html .= $close;
    $html .= "</td></tr>";

    $html .= "<tr><th>Open</th><td>";
    $html .= $recentDay['1. open'];
    $html .= "</td></tr>";

    $previousDay = getAttributeByIndex($obj1, 1);
    $html .= "<tr><th>Previous Close</th><td>";
    $previous_close = $previousDay['4. close'];
    $html .= $previous_close;
    $html .= "</td></tr>";

    $html .= "<tr><th>Change</th><td>";
    if ($close > $previous_close) {
        $change = $close - $previous_close;
        $html .= number_format($change, 2, '.', ',');
        $html .= "<img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png\" height=18 width=18/>";
    } else {
        $change = $previous_close - $close;
        $html .= number_format($change, 2, '.', ',');
        $html .= "<img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png\" height=18 width=18/>";
    }
    $html .= "</td></tr>";

    $html .= "<tr><th>Change Percent</th><td>";
    $percent = 100 * $change / $previous_close;
    $html .= number_format($percent, 2, '.', ',') . "%";
    if ($close > $previous_close) {
        $html .= "<img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png\" height=18 width=18/>";
    } else {
        $html .= "<img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png\" height=18 width=18/>";
    }
    $html .= "</td></tr>";

    $html .= "<tr><th>Day's Range</th><td>";
    $html .= $recentDay['3. low'] . "-" . $recentDay['2. high'];
    $html .= "</td></tr>";

    $html .= "<tr><th>volume</th><td>";
    $html .= number_format($recentDay['5. volume']);
    $html .= "</td></tr>";

    $html .= "<tr><th>Timestamp</th><td>";
    $html .= key($obj1);
    $html .= "</td></tr>";

    $html .= "<tr><th>Indicators</th><td>";
    $html .= "<span id='priceLink' onclick=\"document.getElementById('btnSearch').click()\">Price</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "<span id='SMALink' onclick=\"showGraph1(inputBox.value,'SMA')\">SMA</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "<span id='EMALink' onclick=\"showGraph1(inputBox.value,'EMA')\">EMA</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "<span id='STOCHLink' onclick=\"showGraph2(inputBox.value,'STOCH')\">STOCH</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "<span id='RSILink' onclick=\"showGraph1(inputBox.value,'RSI')\">RSI</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "<span id='ADXLink' onclick=\"showGraph1(inputBox.value,'ADX')\">ADX</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "<span id='CCILink' onclick=\"showGraph1(inputBox.value,'CCI')\">CCI</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "<span id='BBANDSLink'onclick=\"showGraph3(inputBox.value,'BBANDS')\">BBANDS</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "<span id='MACDLink' onclick=\"showGraph3(inputBox.value,'MACD')\">MACD</span>&nbsp;&nbsp;&nbsp;&nbsp;";
    $html .= "</td></tr>";

    $html .= "</table></div>";
    $html .= "<div id=\"toGraph\" style=\"width:1319px; height:600px; margin:0 auto;\"></div>";
    $html .= "<div id=\"toNews\" style=\"width:1319px; margin:10px auto;\"><p id = \"newsIndicator\" style=\"color:grey; font-size: 20px;\">click to show stock news</p><img id='downArrow' src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png\" width='30' onclick='myFunction()'/></div>";

    if($ticker === ""){
        $html = "";
    }
    else if (key($obj) === "Error Message") {
        $html = "<div id=\"toTable\"><table><tr><th>Error</th><td>";
        $html .= "Error: No recored has been found, please enter a valid symbol";
        $html .= "</td></tr></table></div>";
    }

    echo $html;
}

function dayClose($obj){
    $obj1 = $obj['Time Series (Daily)'];
    $dataArray = array();
    $dataLength = count($obj1);
    for($i = 0; $i < $dataLength; $i++){
        $day = getAttributeByIndex($obj1,$i);
        $data = $day['4. close'];
        array_push($dataArray, $data);
    }
    return $dataArray;
}
function dayVolume($obj){
    $obj1 = $obj['Time Series (Daily)'];
    $dataArray = array();
    $dataLength = count($obj1);
    for($i = 0; $i < $dataLength; $i++){
        $day = getAttributeByIndex($obj1,$i);
        $data = $day['5. volume'];
        array_push($dataArray, $data/1000000);
    }
    return $dataArray;
}
function getAttributeByIndex($obj, $index){
    $i = 0;
    foreach ($obj as $attr){
        if ($index === $i){
            return $attr;
        }
        $i++;
    }
    return null;
}

?>
</body>

<script type="text/javascript">
    var inputBox = document.getElementById('tickerInput');
    var apiLink = "&apikey=OE0QXT6U0BKFHVV8";
    var inputDate = Date.now() - 24 * 3600000 * 100;
    function clearInput(){
        inputBox.value = "";
        document.getElementById("toTable").remove();
        document.getElementById("toGraph").remove();
    }
    function searchValue(){
        var tickerInput = inputBox.value;
        var regex = /\w+/;
        var res = regex.test(tickerInput);
        if(!res){
            alert("Please enter a symbol");
        }
    }

    function showGraph1(symbol, indicator) {
        var url = "https://www.alphavantage.co/query?function=";
        url += indicator;
        url += "&symbol=" + symbol;
        url += "&interval=weekly&time_period=10&series_type=open&apikey=" + apiLink;
        var to_json = loadXML(url);
        var json = JSON.parse(to_json);

        var obj0 = json["Meta Data"];
        var indi = "Technical Analysis: " + indicator;
        var obj1 = json[indi];

        var indicatorData = [];
        for(var i = 0; i < 100; i++){
            indicatorData.push(getAttributeByIndex(obj1, i)[indicator]);
        }
        indicatorData = indicatorData.map(Number);

        Highcharts.chart('toGraph', {

            chart: {
                borderColor: '#d8d8d8',
                borderWidth: 1,
                marginRight: 200,
                type: 'line'
            },
            title: {
                text: obj0["2: Indicator"]
            },
            subtitle: {
                text: '<a href="https://www.alphavantage.co/" style="color: blue">Source: Alpha Vantag</a>'
            },
            yAxis: {
                title: {
                    text: indicator
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: 10,
                y: 270
            },
            xAxis : {
                type: 'datetime',
                labels: {
                    format: '{value:%m/%d}'
                }
                //minRange: 99 * 24 * 3600000 // fourteen days
            },
            series: [{
                name: symbol,
                color: 'red',
                pointInterval: 24 * 3600000,
                pointStart: inputDate,
                data: indicatorData
            }]
        });

    }
    function showGraph2(symbol, indicator) {
        var url = "https://www.alphavantage.co/query?function=";
        url += indicator;
        url += "&symbol=" + symbol;
        url += "&interval=weekly&time_period=10&series_type=open&apikey=" + apiLink;
        var to_json = loadXML(url);
        var json = JSON.parse(to_json);

        var obj0 = json["Meta Data"];
        var indi = "Technical Analysis: " + indicator;
        var obj1 = json[indi];

        var indicatorDataD = [];
        var indicatorDataK = [];
        for(var i = 0; i < 100; i++){
            indicatorDataD.push(getAttributeByIndex(obj1, i)["SlowD"]);
            indicatorDataK.push(getAttributeByIndex(obj1, i)["SlowK"]);
        }
        indicatorDataD = indicatorDataD.map(Number);
        indicatorDataK = indicatorDataK.map(Number);

        Highcharts.chart('toGraph', {

            chart: {
                borderColor: '#d8d8d8',
                borderWidth: 1,
                marginRight: 200,
                type: 'line'
            },
            title: {
                text: obj0["2: Indicator"]
            },
            subtitle: {
                text: '<a href="https://www.alphavantage.co/" style="color: blue">Source: Alpha Vantag</a>'
            },
            yAxis: {
                title: {
                    text: indicator
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: 10,
                y: 270
            },
            xAxis : {
                type: 'datetime',
                labels: {
                    format: '{value:%m/%d}'
                }
                //minRange: 99 * 24 * 3600000 // fourteen days
            },
            series: [{
                name: symbol + ' SlowD',
                color: 'red',
                pointInterval: 24 * 3600000,
                pointStart: inputDate,
                data: indicatorDataD
            },{
                name: symbol + ' SlowK',
                pointInterval: 24 * 3600000,
                pointStart: inputDate,
                data: indicatorDataK
            }]
        });
    }
    function showGraph3(symbol, indicator) {
        var url = "https://www.alphavantage.co/query?function=";
        url += indicator;
        url += "&symbol=" + symbol;
        url += "&interval=weekly&time_period=10&series_type=open&apikey=" + apiLink;
        var to_json = loadXML(url);
        var json = JSON.parse(to_json);

        var obj0 = json["Meta Data"];
        var indi = "Technical Analysis: " + indicator;
        var obj1 = json[indi];

        var indicatorData1 = [];
        var indicatorData2 = [];
        var indicatorData3 = [];
        if(indicator === "BBANDS"){
            var indi1 = "Real Upper Band";
            var indi2 = "Real Middle Band";
            var indi3 = "Real Lower Band";
        } else {
            indi1 = "MACD_Signal";
            indi2 = "MACD_Hist";
            indi3 = "MACD";
        }
        for(var i = 0; i < 100; i++){
            indicatorData1.push(getAttributeByIndex(obj1, i)[indi1]);
            indicatorData2.push(getAttributeByIndex(obj1, i)[indi2]);
            indicatorData3.push(getAttributeByIndex(obj1, i)[indi3]);
        }
        indicatorData1 = indicatorData1.map(Number);
        indicatorData2 = indicatorData2.map(Number);
        indicatorData3 = indicatorData3.map(Number);

        Highcharts.chart('toGraph', {

            chart: {
                borderColor: '#d8d8d8',
                borderWidth: 1,
                marginRight: 200,
                type: 'line'
            },
            title: {
                text: obj0["2: Indicator"]
            },
            subtitle: {
                text: '<a href="https://www.alphavantage.co/" style="color: blue">Source: Alpha Vantag</a>'
            },
            yAxis: {
                title: {
                    text: indicator
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: 10,
                y: 270
            },
            xAxis : {
                type: 'datetime',
                labels: {
                    format: '{value:%m/%d}'
                }
                //minRange: 99 * 24 * 3600000 // fourteen days
            },
            series: [{
                name: symbol + ' ' + indi1,
                color: 'red',
                pointInterval: 24 * 3600000,
                pointStart: inputDate,
                data: indicatorData1
            },{
                name: symbol + ' ' + indi2,
                pointInterval: 24 * 3600000,
                pointStart: inputDate,
                data: indicatorData2
            },{
                name: symbol + ' ' + indi1,
                pointInterval: 24 * 3600000,
                pointStart: inputDate,
                data: indicatorData3
            }]
        });
    }

    function loadXML(url) {
        var xmlhttp, xmlDoc;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.open("GET", url, false);
        xmlhttp.send();

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            xmlDoc = xmlhttp.responseText;
            return xmlDoc;
        } else {
            alert("Please enter a valid symbol!");
            exit();
        }
    }

    function getAttributeByIndex(obj, index){
        var i = 0;
        for (var attr in obj){
            if (index === i){
                return obj[attr];
            }
            i++;
        }
        return null;
    }

    function myFunction() {
        var x = document.getElementById("newsContent");
        if (x.style.display === "inline-block") {
            x.style.display = "none";
        } else {
            x.style.display = "inline-block";
        }
        var y = document.getElementById("newsIndicator");
        if (y.innerHTML === "click to show stock news") {
            y.innerHTML = "click to hide stock news";
        } else {
            y.innerHTML = "click to show stock news";
        }
        var z = document.getElementById("downArrow");
        if (z.src === "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png") {
            z.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png";
        } else {
            z.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png";
        }
    }

    //document.getElementById("downArrow").addEventListener("click", myFunction);


</script>

</html>
