/**
 * @author Ken Lowrie
 */
"use strict";

function prepareTooltips(){
	$(document).tooltip({
		show: true,
		track: true,
		open: function (event, ui) {
			setTimeout(function() {
				$(ui.tooltip).hide(true);
			},2000);
		}
	});
}

function disableAccordion(){
	$("div.quizPuzzle").accordion("destroy");
}

function enableAccordion($open){
	if(typeof($open) === 'undefined') $open = 0;
	$("div.quizPuzzle").accordion({
		collapsible: true,
		active: $open
	});
}

/*
function prepareAjaxSpinner(){
	var $body = $("body");
	// could not get this to work at all (with on() or bind()....)
	// So instead, I just added this to my getJSON code below...
	
	$("document").on({		
		ajaxStart: function () { $body.addClass("loading");		},
		 ajaxStop: function () { $body.removeClass("loading");	}
	});
}
*/

function prepareMKMS() {
	//Check for existance before doing this -- initially (or after reset), nothing is there...
	if ($('#quizHelp').length ){
		$('#quizHelp').click(function() {
			// If the help div's are currently visible, then:
			if($(".quizOptionRight").is(':visible')){
				// Hide them
				$(".quizOptionRight").hide();
			} else {
				// Show them
				$(".quizOptionRight").show();
			}
		});
		// now hide it on the initial page load.
		$(".quizOptionRight").hide();
	}
	prepareTooltips();
}

function isTextNode(){
	return (this.nodeType === 3);
}

function resetDocumentData(pvID,object,key,value){
	var mynode = $(pvID).children("div")[0];
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
	//TODO: Decide if we need a spinner here for calls that take a little bit to complete...
	console.log('regenPuzzleObject('+variant+','+object+','+pvID+')');
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
		//Turn the spinner off here
		$("body").removeClass("loading");
	});
	// Turn the spinner on here
	// Have a DIV with the spinner prepositioned in the right place so we can enable/disable on the fly
	$("body").addClass("loading");
}

function prepareMAKEQUIZ(variant,object) {
	prepareTooltips();
	enableAccordion();
	$(".hideMe").hide();
	//prepareAjaxSpinner();
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
