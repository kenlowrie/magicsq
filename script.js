/**
 * @author Ken Lowrie
 */
"use strict";

function prepareMKMS() {
	//TODO: Need to check for existance before doing this -- sometimes this div isn't there...
	document.getElementById("quizHelp").onclick = function() {
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

function isTextNode(){
	return (this.nodeType === 3);
}

function resetDocumentData(pvID,object,key,value){
	var mynode = $(pvID).children("div")[0];
	//var curNotes = $(pvID).find(".magicSquareNotes").html();
	switch(key){
		case "notes":
			var decoded = $('<textarea/>').html(value).val();
			$(pvID).find(".magicSquareNotes").html(decoded);		
			break;
		case "square":
			var decoded = $('<textarea/>').html(value).val();
			$(pvID).find(".puzzleSquare").html(decoded);	
			//console.log($(pvID).find(".puzzleSquare").html());
			break;
		case "puzzle":
			var decoded = $('<textarea/>').html(value).val();
			$(pvID).find(".puzzleTerms").html(decoded);		
			break;
		default:
			console.log('I got a key back that I cannot handle ['+key+']');
			break;
	}
}

function regenPuzzleObject(variant,object,pvID){
	console.log('inside prepareMAKEQUIZ('+variant+','+object+','+pvID+')');
	var jqxhr = $.getJSON('msjsonp.php?callback=?','variant='+variant+'&item='+object,function(data){
    		console.log('success:');
    		$.each(data, function( key, val) {
    			console.log(key + "=" + val.substr(0,25) + ' ... ' + val.substr(-25));
	    		resetDocumentData(pvID,object,key,val);			
    		});
	})
	.done(function() {
		//console.log("second success");
	})
	.fail(function() {
		console.log("error ");
	})
	.always(function() {
		//console.log("complete");
	});
}

function prepareMAKEQUIZ(variant,object) {
	document.getElementById("myElement").onclick = function() {
		regenPuzzleObject(0,1,'myid');
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
