# Web Technology

@(IT Studies)


## HTML
-------------------

### `<a>`
· Use the `href` attribute to define the link address
· Use the `href` attribute (href = "#value") to link to the bookmark
· Use the `target` attribute to define where to open the linked document
· Use the `<img>` element (inside `<a>`) to use an image as a link

###`<img>` 
Use the HTML `src` attribute to define the URL of the image
Use the HTML `alt` attribute to define an alternate text for an image, if it cannot be displayed
Use the CSS `float` property to let the image float
Use the HTML `<map>` element to define an image-map
Use the HTML `<area>` element to define the clickable areas in the image-map
Use the HTML `<img>`'s element usemap attribute to point to an image-map

###`<table>` 
Use the HTML `<tr>` element to define a table row
Use the HTML `<td>` element to define a table data
Use the HTML `<th>` element to define a table heading
Use the HTML `<caption>` element to define a table caption
Use the CSS `border` property to define a border
Use the CSS `border-collapse` property to collapse cell borders
Use the CSS `padding` property to add padding to cells
Use the CSS `text-align` property to align cell text
Use the CSS `border-spacing` property to set the spacing between cells
Use the `colspan` attribute to make a cell span many columns
Use the `rowspan` attribute to make a cell span many rows
table#t01 `tr:nth-child(odd)`

### list 
Use the HTML `<ul>` element to define an unordered list
Use the CSS `list-style-type` property to define the list item marker
Use the HTML `<ol>` element to define an ordered list
Use the HTML `type` attribute to define the numbering type
Use the HTML `<li>` element to define a list item
####description list:
**`<dl>` element to define a description list
**`<dt>` element to define the description term
**`<dd>` element to describe the term in a description list
** Use the CSS property `float:left` or `display:inline` to display a list horizontally

###`<form>`
*   disable, readonly, size, maxlength...
####`<input>`
*   text, submit, reset, radio, checkbox: name, value
*   button: onclick, value
*   password
*   range, color, date, email…...
####`<select>`: option value
####`<textarea>`: rows, cols


## CSS
-------------------

### `<a>`
- a:link
- a:visited
- a:hover
- a:active

### button
- border-style: inset
- box-shadow: 3px 3px red, -1em 0 0.4em olive;
- ...

### Icon (Awesome, Bootstrap...)
- `<i class="fa fa-cloud"></i>`

### Display
- block: for 行状，`<span>`, `<a>`, `<img>`
- inline: for 块状, `<li>`
- inline-block: `<div>`

### Media
```css
@media screen and (min-width: 480px) {
    #leftsidebar {width: 200px; float: left;}
    .main {margin-left:216px;}
}
```

## JavaScript
-------------------

- `document.write("Hello");`
`document.getElementByID(‘id’).innerHTML = "Hello";`
- Object cannot be compared.
    > <b>== means value, === means same

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