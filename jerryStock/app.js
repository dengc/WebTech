/**
 * Copyright 2017, Google, Inc.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

'use strict';

// [START app]
var express = require('express');
var app = express();
var app2 = express();
var request = require('request');

var parseString = require('xml2js').parseString;
var url = require("url");


app.get('/', function(req, res){
    res.status(200).sendfile('./index.html');
});

app2.get('/', function(req, res) {
    var url_stock = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
    var myAPI_stock = "&apikey=OE0QXT6U0BKFHVV8";
    var params = url.parse(req.url,true).query;
    var symbol = params.symbol;

    url_stock = url_stock + symbol + myAPI_stock;

    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");

    var url_indicator = "https://www.alphavantage.co/query?function=";
    var indicator = params.function;

    url_indicator += indicator;
    url_indicator += "&symbol=" + symbol;
    url_indicator += "&interval=daily&time_period=10&series_type=open" + myAPI_stock;

    if(!JSON.stringify(indicator)){
        request(url_stock, function(error, response, body) {
            res.send(body);
        });
    }
    else {
        request(url_indicator, function(error, response, body) {
            res.send(body);
        });
    }
});
app2.get('/mob/:symbol', function(req, res){
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");

    var url_stock = "http://homework-8-1267.appspot.com/getInfoMobile.php?getstock=";
    // var myAPI_stock = "&apikey=OE0QXT6U0BKFHVV8";

    var symbol = req.params.symbol;
    var url = url_stock + symbol;
    request(url, function(error, response, body) {
        body = JSON.parse(body);
        var lastPrice = body["lastPrice"];
        var change = body["change"];
        var changePercent = body["changePercent"];
        var time = body["timeAndDate"];
        var volume = body["volume"];
        var high = body["highPrice"];
        var low = body["lowPrice"];
        var open = body["openingPrice"];
        var json = {
            "Stock Symbol":body["symbol"],
            "Last Price":lastPrice.substring(lastPrice.indexOf("$") +2, lastPrice.length),
            "Change": change + " " + changePercent,
            "Timestamp": time,
            "Open":open.substring(open.indexOf("$") +2, open.length),
            "Close":high.substring(high.indexOf("$") +2, high.length),
            "Day's Range":low.substring(low.indexOf("$") +2, low.length) + " - " + high.substring(high.indexOf("$") +2, high.length),
            "Volume": volume
        };
        res.send(json);
    });
});

app2.get('/:symbol', function(req, res){
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");
    var symbol = req.params.symbol;
    var news_url = "https://seekingalpha.com/api/sa/combined/";
    news_url += symbol;
    request(news_url, function(error, response, body) {
        parseString(body, function (err, result) {
            res.send(result);
        });
    });
});

app2.get('/auto/:symbol', function(req, res){
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");
    var symbol = req.params.symbol;
    var url_auto = "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/jsonp?input=" + symbol;

    request(url_auto, function(error, response, body) {
        body = body.substr(body.indexOf("[{"));
        body = body.replace(")", "");
        body = JSON.parse(body);
        res.send(body);
    });
});

// Start the server
const PORT = process.env.PORT || 8081;
app.listen(PORT, function() {
  console.log('http://localhost:8081/');
});
app2.listen("8082", function() {
    console.log('http://localhost:8082/');
});
// [END app]
