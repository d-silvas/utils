var x = document.evaluate('//comment()', document, null, XPathResult.ANY_TYPE, null),
    comment = x.iterateNext();

while (comment) {
    console.log(comment.textContent);
    comment = x.iterateNext();
}
