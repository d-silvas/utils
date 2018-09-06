// JQUERY
var call = function() { $.get('http://10.10.10.148/appform/',function(data,status) {
      return 0;
},'html')};

// PLAIN JS
var httpGetAsync = function (theUrl, callback)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.responseText);
    }
    xmlHttp.open("GET", theUrl, true);
    xmlHttp.send(null);
}
httpGetAsync("http://10.10.10.148/appform/", function(text) {
    console.log(text);
});
