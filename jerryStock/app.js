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
var request = require('request');

var parseString = require('xml2js').parseString;
var cors = require('cors');
var url = require("url");
// app.set('view engine', 'html');
app.use(cors());
var url_stock = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
var symbol;

app.get('/', function(req, res){
    res.status(200).sendfile('./index.html');

    var myAPI_stock = "&apikey=OE0QXT6U0BKFHVV8";
    var params = url.parse(req.url,true).query;
    symbol = params.symbol;
     // console.log(symbol);
    url_stock = url_stock + symbol + myAPI_stock;

});
app.get('/s', function(req, res) {

    // var params = url.parse(req.url,true).query;
    // symbol = params.symbol;
    console.log(symbol);
    console.log(url_stock);
    request(url_stock, function(error, response, body) {
        res.send(body);
    });

});

// Start the server
const PORT = process.env.PORT || 8081;
app.listen(PORT, function() {
  console.log('http://localhost:8081/');
});
// [END app]
