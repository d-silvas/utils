var strings = [];

function findIds() {
    $('[id]').each(function() {
      strings.push($(this).attr("id"));
    });
    var lines = $("#lookup-file").text().split('\n');
    for(var i = 0; i < lines.length; i++){
        for (var j = 0; j < strings.length; j++) {
            if (strings[j].length !== 0 && lines[i].includes(strings[j])) {
                console.log(`"#${strings[j]}" found on line ${i}`);
            }
        }
    }
}
function findClasses() {
    $('[class]').each(function() {
        var classes = $(this).attr("class").split(" ");
        classes.forEach(function (cl) {
            strings.push("" + cl);
        });
    });
    var lines = $("#lookup-file").text().split('\n');
    for(var i = 0; i < lines.length; i++){
        for (var j = 0; j < strings.length; j++) {
            if (strings[j].length !== 0 && lines[i].includes(strings[j])) {
                console.log(`"${strings[j]}" found on line ${i}`);
            }
        }
    }
}

/*** Put this in the html

<!-- FILE FOR ID FINDING -->
<div id="lookup-file" type="text/javascript" src=""><pre>
<?= file_get_contents(base_url("js/index.js")) ?>
</pre></div>

<script type="text/javascript" src="<?= base_url("js/find-in-file.js") ?>"></script>

***/
