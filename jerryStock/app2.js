const express = require('express');

const app = express();
var request = require('request');
var url = require("url");
var parseString = require('xml2js').parseString;


app.get('/', (req, res) => {
    var url_stock = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
    var myAPI_stock = "&apikey=OE0QXT6U0BKFHVV8";
    var params = url.parse(req.url,true).query;
    var symbol = params.symbol;

    url_stock = url_stock + symbol + myAPI_stock;
    // console.log(symbol);
    // console.log(url_stock);
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
            res.status(200).send(body).end();
            
        });
    }
    else {
        request(url_indicator, function(error, response, body) {
            res.status(200).send(body).end();
        });
    }
});
app.get('/:symbol', (req, res) => {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With");
    var symbol = req.params.symbol;
    var news_url = "https://seekingalpha.com/api/sa/combined/";
    news_url += symbol;
    request(news_url, function(error, response, body) {
        parseString(body, function (err, result) {
            res.status(200).send(result).end();

        });
    });
});

// Start the server
const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
  console.log(`App listening on port ${PORT}`);
  console.log('Press Ctrl+C to quit.');
});