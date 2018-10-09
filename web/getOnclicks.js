// Gets all onclick="" definitions on the page
var allElements = document.getElementsByTagName('*');
for ( var i = 0; i<allElements.length; i++ ) {
    if ( typeof allElements[i].onclick === 'function' ) {
        console.log( allElements[i].getAttribute('onclick') );
    }
}
