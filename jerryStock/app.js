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

app.get('/', function(req, res){
  res.status(200).send('Hello Jerry Nodejs!').end();
});

// Start the server
const PORT = process.env.PORT || 8081;
app.listen(PORT, function() {
  console.log('http://localhost:8081/');
});
// [END app]
