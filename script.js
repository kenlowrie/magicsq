/**
 * @author Ken Lowrie
 */
"use strict";

function prepareMKMS() {
	document.getElementById("selectOptions").onclick = function() {
		// if (document.getElementById("selectOptions").style.display === "none"){
			// document.getElementById("selectOptions").style.display = "block";
		// } else{
			// document.getElementById("selectOptions").style.display = "none";
		// }
		if($(".quizOptionRight").is(':visible')){
			$(".quizOptionRight").hide();
		} else {
			$(".quizOptionRight").show();
		}
	};
	// now hide it on the initial page load.
	$(".quizOptionRight").hide();
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
