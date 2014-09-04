/**
 * @author Ken Lowrie
 */
"use strict";

function prepareMKMS() {
	document.getElementById("loadTerms").onclick = function() {
		// if (document.getElementById("brochures").checked) {
			// // use CSS style to show it
			// document.getElementById("tourSelection").style.display = "block";
		// } else {
			// // hide the div
			// document.getElementById("tourSelection").style.display = "none";
		// }
		if (document.getElementById("selectOptions").style.display === "none"){
			document.getElementById("selectOptions").style.display = "block";
		} else{
			document.getElementById("selectOptions").style.display = "none";

		}
	};
	// now hide it on the initial page load.
//	document.getElementById("tourSelection").style.display = "none";
}


function prepareMAKEQUIZ() {
	console.log('inside prepareMAKEQUIZ()');
	document.getElementById("myElement").onclick = function() {
		var jqxhr = $.getJSON('msjsonp.php?callback=?','variant=1&item=ms',function(data){
	    		console.log('success:');
	    		$.each(data, function( key, val) {
	    			console.log(key + "=" + val);
	    		});
		})
		.done(function() {
			console.log("second success");
		})
		.fail(function() {
			console.log("error ");
		})
		.always(function() {
			console.log("complete");
		});
		//console.log("from jqxhr: " + jqxhr.statusText);
	};
}

var MYJSLIB = MYJSLIB || (function(){
	//var	_args = {};		// This would be a private variable to all the closures below
	
	return {
		// init : function(Args) {	// A generic example of passing in args and assigning to private _args for later...
			// _args = Args;
			// // More stuff here;
		// },
		mkmsInit : function () {
			window.onload =  function() {
				prepareMKMS();
			};
		},
		makequizInit : function () {
			// args can be passed/access via the init and then myarg = _args[0...]
			window.onload = function () {
				prepareMAKEQUIZ();
			};
		}
	};
}());

// 
// window.onload =  function() {
	// preparePage2();
	// preparePage();
// };
// 
