# Web Technology

@(IT Studies)


## HTML
-------------------

- `<link>`: provide a variety of information to search engines
- meta: insert Name/Value pairs describing document properties... Moving a Web page to a new site
- applet: plug-in & embed: (type)flash, video...

### `<a>`
- Use the `href` attribute to define the link address
- Use the `href` attribute (href = "#value") to link to the bookmark
- Use the `target` attribute to define where to open the linked document
- Use the `<img>` element (inside `<a>`) to use an image as a link
- `<a href="https://www.w3schools.com/" target="_blank">W3Schools!</a>`

### `<img>` 
- Use the HTML `src` attribute to define the URL of the image
- Use the HTML `alt` attribute to define an alternate text for an image, if it cannot be displayed
- Use the CSS `float` property to let the image float
- Use the HTML `<map>` element to define an image-map
- Use the HTML `<area>` element to define the clickable areas in the image-map
- Use the HTML `<img>`'s element usemap attribute to point to an image-map
- `<img src="html5.gif" alt="HTML5 Icon" width="128" height="128">`

### `<table>` 
- Use the HTML `<tr>` element to define a table row
- Use the HTML `<td>` element to define a table data
- Use the HTML `<th>` element to define a table heading
- Use the HTML `<caption>` element to define a table caption
- Use the CSS `border` property to define a border
- Use the CSS `border-collapse` property to collapse cell borders
- Use the CSS `padding` property to add padding to cells
- Use the CSS `text-align` property to align cell text
- Use the CSS `border-spacing` property to set the spacing between cells
- Use the `colspan` attribute to make a cell span many columns
- Use the `rowspan` attribute to make a cell span many rows
- table#t01 `tr:nth-child(odd)`

### list 
- Use the HTML `<ul>` element to define an unordered list
- Use the CSS `list-style-type` property to define the list item marker
- Use the HTML `<ol>` element to define an ordered list
- Use the HTML `type` attribute to define the numbering type
- Use the HTML `<li>` element to define a list item
- Use the CSS property `float:left` or `display:inline` to display a list horizontally
- `<ol type="a">   <li>Coffee</li>   <li>Tea</li> </ol>`
#### description list:
**`<dl>` element to define a description list
**`<dt>` element to define the description term
**`<dd>` element to describe the term in a description list

### `<form>`
*   disable, readonly, size, maxlength...
#### `<input>` (type)
*   text, submit, reset, radio, checkbox: name, value
*   button: onclick, value
*   password
*   range, color, date, email…...
#### `<select>`: option value, size
#### `<textarea>`: rows, cols

### `<Fieldset>`
- divide a form into smaller, with `<legend>` as caption


## CSS
-------------------

- :first-child, :first-line, :first-letter, :before, :after....

```css
#header h1.p {}
```
match
```htmlbars
<div id="header">
    <h1 class="p">
        This is my phrase
    </h1>
</div>
```

### `<a>`
- a:link
- a:visited
- a:hover
- a:active

### button
- border-style: inset
- box-shadow: 3px 3px red, -1em 0 0.4em olive;

### Icon (Awesome, Bootstrap...)
- `<i class="fa fa-cloud"></i>`

### Display
- block: for 行状，`<span>`, `<a>`, `<img>`
- inline: for 块状, `<li>` -> 横排排列
- inline-block: `<div>`
- 应对float: 巧用clear, `clear: left;`

### Media
```css
@media screen and (max-width: 480px) {
    #leftsidebar {width: 200px; float: left;}
    .main {margin-left:216px;}
}
```


## JavaScript
-------------------

- `document.write("Hello");`
`document.write("<BR>");`
`document.write(eval("2+2"));`
`document.myform.firstname.value != ""`
`document.getElementById("myBtn").addEventListener("click", displayDate);`

- If a variable is NOT declared using var, then it is global.
- Object cannot be compared.
    > <b>== means value, === means same
    
- `for (p in person)`
- `name1 = prompt("What is your name? ", " ");`

### DOM
- `document.getElementById("sample").style.color=`
- `document.getElementByID(‘id’).innerHTML = "Hello";`
- childNodes, parentNode, nodeType, nodeName, nodeValue
- removeChild(), appendChild(),

### XMLHttpRequest
```javascript
function loadXML(url) {
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    try {
        xmlhttp.open("GET", url, false);
        xmlhttp.send();
    } catch (err) {
        alert("Please enter a valid name.");
    }
    xmlDoc = xmlhttp.responseText;
    return xmlDoc;
}

var json = JSON.parse(loadXML(url));
```
```javascript
//open another window
var content = window.open("", "XML Result", "height=730, width= 1050, left=25,resizable=yes,scrollbars=yes");
content.document.write(html);
```

### XML
- DTD
- getElementsByTagName, item(i), childNodes, nodeType==1, firstChild. nodeValue


## PHP
-------------------

- `foreach ($colors as $c)`
- `urlencode`: space == +, for query string
`rawurlencode`: space == %20, for path

### variables
- case-sensitive
- `$var = “I” . “ love”;` -> I love
- `$var = "\"CS\"";` -> "CS"

### Super global variables
- `$_GET`: from  url
- `$_POST`: from form
- `$_COOKIE`
- `$_SESSION`
> The `$_POST` variable is also used to collect data from forms, but the `$_POST` is slightly different because in `$_GET` it displayed the data in the url and `$_POST` does not. 

Associate array 
> `$row[‘id’] = $id;`
`$row = array(‘id’ => $id);`

### Sessions
- store their identifier in a cookie in the client’s browser
```php
<?php
    session_start();
    if (!$_SESSION["count"])
        $_SESSION["count"] = 0;
    if ($_GET["count"] == "yes")
        $_SESSION["count"] = $_SESSION["count"] + 1;
?>
```

### connect to db
```php
$link = mysql_connect($host, $user, $password);
Mysql_select_db($database);
$query = “SELECT * FROM $table;”;
$result = mysql_query($query);
mysql_close($link);
```


## HTTP
-------------------
- clients opens a connection; sends HTTP request for documents; send for image
- identifier: url of the resource
- HTTP_Method: 
    - get: info: article
    - post: do sth: add/login
    - put: logout
- 200: success
300: redirection
400: client
500: server


## CGI
-------------------

- create dynamic Web documents.
- script for server
- info from database (optional)
- output: document


## regular expression
-------------------

`/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])\w{8,}$/`
- method
```javascript
var str = "The best";
var regex = new RegExp("e"); // /e/
var t = regex.test(str); //true
var e = regex.exec(str); // e
var m = str.match(regex); // e
```