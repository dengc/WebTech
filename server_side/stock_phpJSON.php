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
<div id="contentToShow">
</div>

<?php

    if(isset($_POST['Search'])){
        $ticker = $_POST["ticker"];
        $url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
        $apiLink = "&apikey=OE0QXT6U0BKFHVV8";


        $json = file_get_contents($url . $ticker . $apiLink);
        $obj = json_decode($json, true);
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
        $change = 0;
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

        }
	}

</script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
</html>
