// 1234567 -> 1,234,567
function setCommas(n) {
    return n.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}



function round0001(x)
{
	return Math.ceil(x * 1000) / 1000;
}

// ex: 7 points between 0 and 10: logspace(0,Math.log10(10),7);
function logspace( a, b, len ) {
	var arr,
		end,
		tmp,
		d;

	if ( typeof a !== 'number' || a !== a ) {
		throw new TypeError( 'logspace()::invalid input argument. Exponent of start value must be numeric.' );
	}
	if ( typeof b !== 'number' || b !== b ) {
		throw new TypeError( 'logspace()::invalid input argument. Exponent of stop value must be numeric.' );
	}
	if ( arguments.length < 3 ) {
		len = 10;
	} /*else {
		if ( !isInteger( len ) || len < 0 ) {
			throw new TypeError( 'logspace()::invalid input argument. Length must be a positive integer.' );
		}
		if ( len === 0 ) {
			return [];
		}
	}*/
	// Calculate the increment:
	end = len - 1;
	d = ( b-a ) / end;

	// Build the output array...
	arr = new Array( len );
	tmp = a;
	arr[ 0 ] = Math.pow( 10, tmp );
	for ( var i = 1; i < end; i++ ) {
		tmp += d;
		arr[ i ] = Math.pow( 10, tmp );
	}
	arr[ end ] = Math.pow( 10, b );
	return arr;
}



function setCookie(cname, cvalue, nminutes) {
  var d = new Date();
  d.setTime(d.getTime() + (nminutes*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function listCookies() {
    var theCookies = document.cookie.split(';');
    var aString = '';
    for (var i = 1 ; i <= theCookies.length; i++) {
        aString += i + ' ' + theCookies[i-1] + "\n";
    }
    return aString;
}

function deleteCookie(cname) {
  document.cookie = cname + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}


/*
	example:
		createDate(0,0,0) --- current date
		createDate(0,-12,0) --- current date - 12 months
*/
function createDate(days, months, years) {
	var date = new Date(); 
	date.setDate(date.getDate() + days);
	date.setMonth(date.getMonth() + months);
	date.setFullYear(date.getFullYear() + years);
	return date.toISOString().split('T')[0];    
}

