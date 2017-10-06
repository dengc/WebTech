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
	//$ticker = $_POST["ticker"];
?>
<form method="post" >
    <p id="title">Stock Search</p>
    <p id="line"></p>
    <label for="tickerInput" id="tickerText">Enter Stock Ticker Symbol:*</label>
    <input type ="text" name ="ticker" id ="tickerInput" />

    <div id="btns">
	    <input id="btnSearch" type ="button"  name="Search" value="Search" onclick="searchValue();"/>
	    <input id="btnClear" type ="button" name="Clear" value ="Clear" onclick="clearInput();" />
    </div>

    <p id="mandText">* - Mandatory fields</p>
</form>
<div id="contentToShow">
<!--    <table id="series">-->
<!--        <tr>-->
<!--            <th>Stock Ticker Symbol</th>-->
<!--            <td>aaaa</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Close</th>-->
<!--            <td>bbbb</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Open</th>-->
<!--            <td>cccc</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Previous Close</th>-->
<!--            <td>dddd</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Change</th>-->
<!--            <td>5555</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Change Percent</th>-->
<!--            <td>6666</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Day's Range</th>-->
<!--            <td>7777</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Volume</th>-->
<!--            <td>8888</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Timestamp</th>-->
<!--            <td>9999</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th>Indicators</th>-->
<!--            <td>Price</td>-->
<!--        </tr>-->
<!---->
<!--    </table>-->
</div>

<?php

?>
</body>
<script type="text/javascript">
    var inputBox = document.getElementById('tickerInput');
    var url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
    var apiLink = "&apikey=OE0QXT6U0BKFHVV8";
	function clearInput(){
        inputBox.value = "";
        inputBox.focus();
	}
	function searchValue(){
		var tickerInput = inputBox.value;
		var regex = /\w+/;
		var res = regex.test(tickerInput);
		if(!res){
			alert("Please enter a symbol");
		}
		else {
            url += tickerInput;
            url += apiLink;
            var to_json = loadXML(url);
            var json = JSON.parse(to_json);
            var obj0 = json["Meta Data"];
            var obj1 = json["Time Series (Daily)"];
            //alert(obj0["1. Information"]);
            var html = "<table><tr><th>Stock Ticker Symbol</th><td>";
            html += tickerInput;
            html += "</td></tr>";

            var recentDay = getAttributeByIndex(obj1, 0);
            html += "<tr><th>Close</th><td>";
            var close = recentDay["4. close"];
            html += close;
            html += "</td></tr>";

            html += "<tr><th>Open</th><td>";
            html += recentDay["1. open"];
            html += "</td></tr>";

            var previousDay = getAttributeByIndex(obj1, 1);
            html += "<tr><th>Previous Close</th><td>";
            var previous_close = previousDay["4. close"];
            html += previous_close;
            html += "</td></tr>";

            html += "<tr><th>Change</th><td>";
            var change = 0;
            if(close > previous_close){
                change = close - previous_close;
                html += change.toFixed(2);
                html += "<img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png\" height=18 width=18/>";
            }else {
                change = previous_close - close;
                html += change.toFixed(2);
                html += "<img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png\" height=18 width=18/>";
            }
            html += "</td></tr>";

            html += "<tr><th>Change Percent</th><td>";
            var percent = 100 * change / previous_close;
            html += percent.toFixed(2) + "%";
            if(close > previous_close){
                html += "<img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png\" height=18 width=18/>";
            }else {
                html += "<img src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png\" height=18 width=18/>";
            }
            html += "</td></tr>";

            html += "<tr><th>Day's Range</th><td>";
            html += recentDay["3. low"] + "-" + recentDay["2. high"];
            html += "</td></tr>";

            html += "<tr><th>volume</th><td>";
            //html += recentDay["5. volume"].toLocaleString();
            html += formatNumber(recentDay["5. volume"]);
            html += "</td></tr>";

            html += "<tr><th>Timestamp</th><td>";
            html += Object.keys(obj1)[0];
            html += "</td></tr>";

            html += "</table>";
            document.getElementById("contentToShow").innerHTML = html;
            //console.log(url);
        }
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
    function formatNumber (num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }
//    document.getElementById("btnSearch").addEventListener("click", displayDate);
//    function displayDate() {
//        document.getElementById("contentToShow").innerHTML = Date();
//    }

</script>
</html>
