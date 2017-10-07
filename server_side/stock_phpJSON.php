<!DOCTYPE HTML>  
<html>
<head>
<style>
    body,p{
        text-align: center;
        margin: 0;
    }
    form{
        display: inline-block;
        margin-top: 5px;
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
        width: 300px;
        text-align: left;
        border: 1px solid #e0e0e0;
        padding: 5px;
    }
    td{
        background-color: #fbfbfb;
        width: 1000px;
        text-align: center;
        border: 1px solid #e0e0e0;
        padding: 5px;
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
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
    <p id="title">Stock Search</p>
    <p id="line"></p>
    <label for="tickerInput" id="tickerText">Enter Stock Ticker Symbol:*</label>
    <input type ="text" name ="ticker" id ="tickerInput" value="<?php echo $ticker;?>"/>

    <div id="btns">
        <input id="btnSearch" type ="submit"  name="Search" value="Search" onclick="searchValue()"/>
        <input id="btnClear" type ="button" name="Clear" value ="Clear" onclick="clearInput();" />
    </div>

    <p id="mandText">* - Mandatory fields</p>
</form>
<div id="contentToShow"></div>
<div id="container" style="width: 1300px; height: 400px; margin: 0 auto"></div>

<?php

    if(isset($_POST['Search'])){
        if($ticker !== "") {


            $json = file_get_contents($url . $ticker . $apiLink);
            $obj = json_decode($json, true);

            //showTable($obj, $ticker);
            //showGraph($obj);
            $dataArray = dayData($obj);
            //print_r($dataArray);
        echo '<script type="text/javascript">',
            //'document.getElementById("container").innerHTML = "sss";',

/*            'var dataArray = \'<?php echo $dataArray;?>\';',*/
/*'var dataArray = \'<?php echo json_encode($dataArray); ?>\';',*/
/*            'var dataArray = <?php echo \'["\' . implode(\'", "\', $dataArray) . \'"]\' ?>;',*/
'var dataArray = ',json_encode($dataArray),';',
            'dataArray = dataArray.map(Number);',
            'alert(dataArray.toString());',
                'Highcharts.chart(\'container\', {
                    chart: {
                        borderColor: \'#d8d8d8\',
                        marginRight: 150,
                        borderWidth: 1
                    },
                    title: {
                        text: \'Stock Price\'
                    },
                    subtitle: {
                        text: \'<a href="https://www.alphavantage.co/" style="color: blue">Source: Alpha Vantag</a>\'
                    },
                    xAxis: [{
                        type: \'datetime\'
                    }],
                    yAxis: [{
                        title: {
                            text: \'Stock Price\'
                        }
                    }, {
                        labels: {
                            format: \'{value}M\',
                
                        },
                        title: {
                            text: \'Volume\'
                        },
                        opposite: true
                
                    }],
                    legend: {
                        align: \'right\',
                        verticalAlign: \'top\',
                        layout: \'vertical\',
                        x: 10,
                        y: 170
                    },
                
                    series: [{
                        type: \'area\',
                        name: \'AAPL\',
                        color: \'#ff898c\',
                        pointInterval: 24 * 3600000,
                        pointStart: Date.UTC(2006, 0, 1),
                        data: dataArray
                    }, {
                        type: \'column\',
                        yAxis: 1,
                        name: \'volume\',
                        color: \'white\',
                        pointInterval: 24 * 3600000,
                        pointStart: Date.UTC(2006, 0, 1),
                        data: dataArray,
                        tooltip: {
                            valueSuffix: \'M\'
                        },
                    }]
                });',
                '</script>';
        }
    }
    function showTable($obj, $ticker){
        $obj1 = $obj['Time Series (Daily)'];

        $html = "<div id=\"toShow\"><table><tr><th>Stock Ticker Symbol</th><td>";
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
        //$change = 0;
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
        $html .= "</table></div>";

        if($ticker === ""){
            $html = "";
        }
        else if (key($obj) === "Error Message") {
            $html = "<div id=\"toShow\"><table><tr><th>Error</th><td>";
            $html .= "Error: No recored has been found, please enter a valid symbol";
            $html .= "</td></tr></table></div>";
        }

        echo $html;
    }
    function dayData($obj){
        $obj1 = $obj['Time Series (Daily)'];
        $dataArray = array();
        $dataLength = count($obj1);
        for($i = 0; $i < $dataLength; $i++){
            $day = getAttributeByIndex($obj1,$i);
            $data = $day['4. close'];
            array_push($dataArray, $data);
        }
        //print_r($dataArray);
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
    var url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
    var apiLink = "&apikey=OE0QXT6U0BKFHVV8";
    function clearInput(){
        inputBox.value = "";
        //inputBox.focus();
        document.getElementById("toShow").remove();
    }
    function searchValue(){
        var tickerInput = inputBox.value;
        var regex = /\w+/;
        var res = regex.test(tickerInput);
        if(!res){
            alert("Please enter a symbol");
        }
        else {
//            url += tickerInput;
//            url += apiLink;
//            var to_json = loadXML(url);
//            var json = JSON.parse(to_json);
            //alert( "<table><tr><th>Error</th><td>Error: No recored has been found, please enter a valid symbol</td></tr></table>");
//            alert(re);
//            return false;
            //chart();

        }
    }
   //var dataArray = '<?php echo $dataArray;?>';
    var dataArray1 = '<?php echo json_encode($dataArray); ?>';
    //alert(dataArray.toString());
    //var dataArray = <?php echo '["' . implode('", "', $dataArray) . '"]' ?>;
    alert(dataArray1.toString());

//    function chart() {
//        Highcharts.chart('container', {
//            chart: {
//                borderColor: '#d8d8d8',
//                marginRight: 150,
//                borderWidth: 1
//            },
//            title: {
//                text: 'Stock Price'
//            },
//            subtitle: {
//                text: '<a href="https://www.alphavantage.co/" style="color: blue">Source: Alpha Vantag</a>'
//            },
//            xAxis: [{
//                type: 'datetime'
//            }],
//            yAxis: [{
//                title: {
//                    text: 'Stock Price'
//                }
//            }, {
//                labels: {
//                    format: '{value}M',
//
//                },
//                title: {
//                    text: 'Stice'
//                },
//                opposite: true
//
//            }],
//            legend: {
//                align: 'right',
//                verticalAlign: 'top',
//                layout: 'vertical',
//                x: 10,
//                y: 170
//            },
//
//            series: [{
//                type: 'area',
//                name: 'AAPL',
//                color: '#ff898c',
//                pointInterval: 24 * 3600000,
//                pointStart: Date.UTC(2006, 0, 1)
//                //data: dataArray
////            data: [
////                0.8446, 0.8445, 0.8444, 0.8451, 0.8418, 0.8264, 0.8258, 0.8232,
////                0.8233, 0.8258, 0.8283, 0.8278, 0.8256, 0.8292, 0.8239, 0.8239,
////                0.8245, 0.8265, 0.8261, 0.8269, 0.8273, 0.8244, 0.8244, 0.8172,
////                0.8139, 0.8146, 0.8164, 0.82, 0.8269, 0.8269, 0.8269, 0.8258,
////                0.8247, 0.8286, 0.8289, 0.8316, 0.832, 0.8333, 0.8352, 0.8357,
////                0.8355, 0.8354, 0.8403, 0.8403, 0.8406, 0.8403, 0.8396, 0.8418,
////                0.8409, 0.8384, 0.8386, 0.8372, 0.839, 0.84, 0.8389, 0.84, 0.8423,
////                0.8423, 0.8435, 0.8422, 0.838, 0.8373, 0.8316, 0.8303, 0.8303,
////                0.8302, 0.8969, 0.84, 0.8385, 0.84, 0.8401, 0.8402, 0.8381,
////                0.8351, 0.8314, 0.8273, 0.8213, 0.8207, 0.8207, 0.8215, 0.8242,
////                0.8273, 0.8301, 0.8346, 0.8312, 0.8312, 0.8312, 0.8306, 0.8327,
////                0.8282, 0.824, 0.8255, 0.8256, 0.8273, 0.8209, 0.8151, 0.8149,
////                0.8213, 0.8273, 0.8273, 0.8261, 0.8252, 0.624, 0.8262, 0.8258,
////                0.8261, 0.826, 0.8199, 0.8153, 0.8097, 0.8101, 0.8119, 0.8107,
////                0.8105, 0.8084, 0.8069, 0.8047, 0.8023, 0.7965, 0.7919, 0.7921,
////                0.7922, 0.7934, 0.7918, 0.7915, 0.787, 0.7861, 0.7861, 0.7853,
////                0.7867, 0.7827, 0.7834, 0.7766, 0.7751, 0.7739, 0.7767, 0.7802,
////                0.5788, 0.7828, 0.7816, 0.7829, 0.783, 0.7829, 0.7781, 0.7811,
////                0.7831, 0.7826, 0.7855, 0.7855, 0.7845, 0.7798, 0.7777, 0.7822,
////                0.7785, 0.7744, 0.7743, 0.7726, 0.7766, 0.7806, 0.785, 0.7907,
////                0.7912, 0.7913, 0.7931, 0.7952, 0.7951, 0.7928, 0.791, 0.7913,
////                0.7912, 0.7941, 0.7953, 0.7921, 0.7919, 0.7968, 0.7999, 0.7999,
////                0.7974, 0.7942, 0.796, 0.7969, 0.7862, 0.7821, 0.7821, 0.7821
////            ]
//            }, {
//                type: 'column',
//                yAxis: 1,
//                name: 'volume',
//                color: 'white',
//                pointInterval: 24 * 3600000,
//                pointStart: Date.UTC(2006, 0, 1),
//
////            data: [
////                0.8446, 0.8445, 0.8444, 0.8451, 0.8418, 0.8264, 0.8258, 0.8232,
////                0.8233, 0.8258, 0.8283, 0.8278, 0.8256, 0.8292, 0.8239, 0.8239,
////                0.8245, 0.8265, 0.8261, 0.8269, 0.8273, 0.8244, 0.8244, 0.8172,
////                0.8139, 0.8146, 0.8164, 0.82, 0.8269, 0.8269, 0.8269, 0.8258,
////                0.8247, 0.8286, 0.8289, 0.8316, 0.832, 0.8333, 0.8352, 0.8357,
////                0.8355, 0.8354, 0.8403, 0.8403, 0.8406, 0.8403, 0.8396, 0.8418,
////                0.8409, 0.8384, 0.8386, 0.8372, 0.839, 0.84, 0.8389, 0.84, 0.8423,
////                0.8423, 0.8435, 0.8422, 0.838, 0.8373, 0.8316, 0.8303, 0.8303,
////                0.8302, 0.8969, 0.84, 0.8385, 0.84, 0.8401, 0.8402, 0.8381,
////                0.8351, 0.8314, 0.8273, 0.8213, 0.8207, 0.8207, 0.8215, 0.8242,
////                0.8273, 0.8301, 0.8346, 0.8312, 0.8312, 0.8312, 0.8306, 0.8327,
////                0.8282, 0.824, 0.8255, 0.8256, 0.8273, 0.8209, 0.8151, 0.8149,
////                0.8213, 0.8273, 0.8273, 0.8261, 0.8252, 0.624, 0.8262, 0.8258,
////                0.8261, 0.826, 0.8199, 0.8153, 0.8097, 0.8101, 0.8119, 0.8107,
////                0.8105, 0.8084, 0.8069, 0.8047, 0.8023, 0.7965, 0.7919, 0.7921,
////                0.7922, 0.7934, 0.7918, 0.7915, 0.787, 0.7861, 0.7861, 0.7853,
////                0.7867, 0.7827, 0.7834, 0.7766, 0.7751, 0.7739, 0.7767, 0.7802,
////                0.5788, 0.7828, 0.7816, 0.7829, 0.783, 0.7829, 0.7781, 0.7811,
////                0.7831, 0.7826, 0.7855, 0.7855, 0.7845, 0.7798, 0.7777, 0.7822,
////                0.7785, 0.7744, 0.7743, 0.7726, 0.7766, 0.7806, 0.785, 0.7907,
////                0.7912, 0.7913, 0.7931, 0.7952, 0.7951, 0.7928, 0.791, 0.7913,
////                0.7912, 0.7941, 0.7953, 0.7921, 0.7919, 0.7968, 0.7999, 0.7999,
////                0.7974, 0.7942, 0.796, 0.7969, 0.7862, 0.7821, 0.7821, 0.7821
////            ],
//                tooltip: {
//                    valueSuffix: 'M'
//                },
//                //data:
//            }]
//        });
//    }


</script>

</html>
