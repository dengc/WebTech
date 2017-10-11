var xmlDoc;

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

    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        xmlDoc = xmlhttp.responseText;
        return xmlDoc;
    } else {
        alert("Please enter a valid name!");
        exit();
    }
}

function display() {
    var textInput = document.getElementById("text_url");
    var url = textInput.value;
    if (url === "") {
        alert("Cannot be empty! Please enter a file name.");
        return;
    }
    to_json = loadXML(url);
    
    try {
        var json = JSON.parse(to_json);
        
    } catch (err) {
        alert("Illegal Page.");
        exit();
    }
    

    row = json.Mainline.Table.Row;

    if (!row || row.length == 0) {
        alert("There are no companies!");
        return;
    }

    if (row[0].Airline.length == 0 && row[0].IATA.length == 0 && (row[0].Hubs.length == 0 || !row[0].Hubs.Hub || row[0].Hubs.Hub.length == 0) && row[0].Notes.length == 0 && row[0].HomePage.length == 0 && row[0].Plane.length == 0) {
        alert("There are no companies!");
        return;
    }

    tableWhole_CSS = "table,th,td{border: 1px solid;}";
    table_CSS = "table{border-top-color: rgb(212, 212, 212);border-left-color: rgb(212, 212, 212);width: 100%;}";
    thtd_CSS = "th,td{border-bottom-color: rgb(212, 212, 212);border-right-color: rgb(212, 212, 212);text-align: center;}";
    td_CSS = "td{text-align: left;}";
    li_CSS = "li:nth-child(1) { font-weight: bold; }";
    img_CSS = "img{height:161px;}";

    var html = "<html><head><title>US Airline Listing</title><style>";
    html += tableWhole_CSS;
    html += table_CSS;
    html += thtd_CSS;
    html += td_CSS;
    html += li_CSS;
    html += img_CSS;
    html += "</style></head><body>" + disTable() + "</body><noscript/></html>";

    var content = window.open("", "XML Result", "height=730, width= 1050, left=25,resizable=yes,scrollbars=yes");
    content.document.write(html);
}

function disTable() {

    var textInput = document.getElementById("text_url");
    var url = textInput.value;
    var json = JSON.parse(loadXML(url));

    //title -- th
    var html = "<table><tr>";
    for (i = 0; i < 6; i++) {
        title = json.Mainline.Table.Header.Data[i];
        html += "<th>" + title + "</th>";
    }
    html += "</tr>";

    //td
    row = json.Mainline.Table.Row;
    height = row.length;
    for (i = 0; i < height; i++) {
        html += "<tr><td>";
        if (row[i].Airline && row[i].Airline.length > 0) {
            html += row[i].Airline;
        }
        html += "</td><td>";
        if (row[i].IATA && row[i].IATA.length > 0) {
            html += row[i].IATA;
        }
        html += "</td>";
        html += "<td style = \" text-align:left; \"> ";
        if (row[i].Hubs) {
            html += "<ul>";
            if (row[i].Hubs.Hub) {
                for (j = 0; j < row[i].Hubs.Hub.length; j++) {
                    html += "<li>";
                    html += row[i].Hubs.Hub[j];
                    html += "</li>";
                }
            }
            html += "</ul>";
        }
        html += "</td>";
        html += "<td>";
        if (row[i].Notes && row[i].Notes.length > 0) {
            html += row[i].Notes;
        }
        html += "</td><td>";
        if (row[i].HomePage && row[i].HomePage.length > 0) {
            html += "<a href= \"" + row[i].HomePage + "\">" + row[i].HomePage; + "</a>";
        }
        html += "</td><td>";
        if (row[i].Plane && row[i].Plane.length > 0) {
            html += "<img src= \"" + row[i].Plane + "\">" + "</img>";
        }
        html += "</td></tr>";
    }
    html += "</table>";
    return html;
}
