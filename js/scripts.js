
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

$("#profile-edit, #author-edit").click(function(){
	$("#main-panel").load("./includes/profile.php"); 
});

});
