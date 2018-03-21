var all = document.querySelectorAll("*");
var tags = [];
all.forEach(function (element) {
  if ( ! tags.includes(element.nodeName)) tags.push(element.nodeName)
});
console.log(tags);
