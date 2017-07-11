
$(document).ready(function(){$(".alert").addClass("in").fadeOut(4500);

/* swap open/close side menu icons */
$('[data-toggle=collapse]').click(function(){
	// toggle icon
	$(this).find("i").toggleClass("glyphicon-chevron-right glyphicon-chevron-down");
});

/*$("#new").click(function(){
                $("#newlesson").load("./includes/newlesson.inc"); 
            });*/

$("#lesson-info").click(function(){
	$("#main-panel").load("./includes/lesson-new.inc"); 
});

$("#page").click(function(){
	$("#main-panel").load("./includes/page.inc"); 
});

$("#question-new").click(function(){
	$("#main-panel").load("./includes/page-quiz.inc"); 
});

$("#quiz-new").click(function(){
	$("#main-panel").load("./includes/quiz-prep-pages.php"); 
});

$("#quiz-list").click(function(){
	$("#main-panel").load("./includes/quiz-list.php"); 
});

$("#question-list").click(function(){
	$("#main-panel").load("./includes/question-list.php"); 
});
$("#question-list-public").click(function(){
	$("#main-panel").load("./includes/question-list-public.php"); 
});
$("#profile-edit, #author-edit").click(function(){
	$("#main-panel").load("./includes/profile.php"); 
});
});

// homepage buttons
$("#add-question").click(function(){
	$("#main-panel").load("./includes/page-quiz.inc"); 
});
$("#add-quiz").click(function(){
	$("#main-panel").load("./includes/quiz-prep-pages.php"); 
});
$("#list-questions").click(function(){
	$("#main-panel").load("./includes/question-list.php"); 
});
$("#list-quizzes").click(function(){
	$("#main-panel").load("./includes/quiz-list.php"); 
});



function cawCKEditor(names)
{	// Convert QW TextAreas (comma separated form names) into CKEditor and use our special config.
	names = names.split(",");
	for (var i in names) {
		name=names[i];
		CKEDITOR.replace( name, {
			customConfig: '/quizwright/js/ckeditor_config.js'
		} );
	}
}
function cawCKEditorUpdates()
{	// Ensure CK fields are AJAX/POST ready.
	for ( instance in CKEDITOR.instances ){
	    CKEDITOR.instances[instance].updateElement();
	}
}
function cawCKEditorLength(editorName)
{
	return messageLength = CKEDITOR.instances[editorName].getData().replace(/<[^>]*>/gi, '').length;
}

         
function cawSetRB(name,val)
{	// Set radio button name to value val.
	val = val || false;
	//console.log('Set '+name+' to '+val+' from '+$('[name='+name+']').val());
	$('[name='+name+'][value='+val+']').click();//("checked", true);
}
function cawLoadCALITopics(selobj, defval)
{	// Load's a SELECT with CALI Topics. to be later replaced with 3 column selector.

// 05/11/2017 SJG List of CALI Topics for tagging questions. This is shared by all question forms so listed once here.
// An option exists to load this list dynamically from database instead of hard coded.
// Listed as 1L then 2L/3L. 
var cawCALITopics = [
"Not specified",
"Civil Procedure",
"Constitutional Law",
"Contracts",
"Criminal Law",
"Legal Concepts and Skills",
"Legal Research",
"Property Law",
"Torts",
"Accounting",
"Administrative Law",
"Animal Law",
"Arbitration",
"Aviation Law",
"Bankruptcy",
"Business Associations",
"Climate Change",
"Commercial Transactions",
"Copyright",
"Corporations",
"Criminal Procedure",
"Employment Discrimination",
"Entertainment Law",
"Environmental Law",
"Evidence",
"Family Law",
"Federal Courts",
"Intellectual Property",
"International Law",
"Negotiable Instruments / Payment Systems",
"Professional Responsibility",
"Patent Law",
"Real Estate Transactions",
"Remedies",
"Sales",
"Securities",
"Tax Law",
"Trademark",
"Trial Advocacy",
"Wills and Trusts"
];

	$(selobj).empty();
	$.each(cawCALITopics, function(i,obj) {$(selobj).append( $('<option></option>') .val(obj ) .html(obj ));});
	if (defval) {
		$(selobj).val(defval);
	}
}

// Load JSON file into a SELECT list
//function loadlist(selobj,url,nameattr){ $(selobj).empty(); $.getJSON(url,{},function(data) { $.each(data, function(i,obj) { $(selobj).append( $('<option></option>') .val(obj[nameattr]) .html(obj[nameattr])); }); }); }
