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
// app.set('view engine', 'html');
// app.use(cors());
//app.use('/?symbol=', stock);
// var url_stock = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
// var symbol;

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
    // console.log(JSON.stringify(indicator));
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

// Start the server
const PORT = process.env.PORT || 8081;
app.listen(PORT, function() {
  console.log('http://localhost:8081/');
});
app2.listen("8082", function() {
    console.log('http://localhost:8082/');
});
// [END app]
