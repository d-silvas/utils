// Get base_url and page from window.location
var urlParts 	= window.location.href.split("/index.php/");
var base_url 	= urlParts[0] + "/";
var page		= urlParts[1] !== undefined ? urlParts[1].split("/")[0] : "";
page 			= page.indexOf("?") !== -1 ? page.split("?")[0] : page; // Fix for whenever there are GET variables on the URL
page 			= page.indexOf("#") !== -1 ? page.split("#")[0] : page; // Fix so that it doesn't change when clicking modals and refreshing

/***
 * DATE AND TIME FORMATTING FUNCTIONS
 ***/
// Returns string YYYY-MM-DD (adds 0s if necessary) -- or null
Date.prototype.toSqlDate = function() {
    if (isValidDate(this)) {
		return `${this.getFullYear()}-${('0' + (this.getMonth()+1)).slice(-2)}-${('0' + this.getDate()).slice(-2)}`;
    }
    return null;
}
// Returns string hh:mm:ss.uuu (adds 0s if necessary) -- or null
Date.prototype.toSqlTime = function() {
    if (isValidDate(this)) {
		var str = `${('0' + this.getHours()).slice(-2)}:${('0' + this.getMinutes()).slice(-2)}:${('0' + this.getMinutes()).slice(-2)}`;
		str += `.${this.getMilliseconds()}`;
		return str;
	}
    return null;
}
// Returns string YYYY-MM-DD HH:mm:ss.000 (adds 0s if necessary) -- or null
Date.prototype.toSqlDatetime = function() {
    if (isValidDate(this)) {
		dateStr = `${this.getFullYear()}-${('0' + (this.getMonth()+1)).slice(-2)}-${('0' + this.getDate()).slice(-2)}`;
		dateStr += ` ${('0' + this.getHours()).slice(-2)}:${('0' + this.getMinutes()).slice(-2)}:${('0' + this.getSeconds()).slice(-2)}.000`;
		return dateStr;
    }
    return null;
}
// Returns string DD/MM/YYYY HH:mm (adds 0s if necessary) -- or null
Date.prototype.toHumanDatetime = function() {
    if (isValidDate(this)) {
        var str = `${('0' + this.getDate()).slice(-2)}/${('0' + (this.getMonth()+1)).slice(-2)}/${this.getFullYear()} `;
		str += `${('0' + this.getHours()).slice(-2)}:${('0' + this.getMinutes()).slice(-2)}`;
		return str;
    }
    return null;
}
// Returns string DD/MM/YYYY (adds 0s if necessary) -- or null
Date.prototype.toHumanDate = function() {
    if (isValidDate(this)) {
        return `${('0' + this.getDate()).slice(-2)}/${('0' + (this.getMonth()+1)).slice(-2)}/${this.getFullYear()}`;
    }
    return null;
}
// Returns string YYYYMMDD (adds 0s if necessary) -- or null
Date.prototype.toFilenameDate = function() {
    if (isValidDate(this)) {
        return `${this.getFullYear()}${('0' + (this.getMonth()+1)).slice(-2)}${('0' + this.getDate()).slice(-2)}`;
    }
    return null;
}
// Returns true if d is a valid JS date object. e.g. if [var d = new Date("foo");], isValidDate(d) returns false
function isValidDate (d) {
	if (Object.prototype.toString.call(d) === "[object Date]") { // it is a date
		if (isNaN(d.getTime())) {  // d.valueOf() could also work
			return false;
		} else { // date is valid
			return true;
		}
	} else { // not a date
		return false;
	}
}
