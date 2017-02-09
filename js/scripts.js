
$(document).ready(function(){$(".alert").addClass("in").fadeOut(4500);

/* swap open/close side menu icons */
$('[data-toggle=collapse]').click(function(){
  	// toggle icon
  	$(this).find("i").toggleClass("glyphicon-chevron-right glyphicon-chevron-down");
});
/*$("#new").click(function(){
                $("#newlesson").load("./includes/newlesson.inc"); 
            });*/
$("#info").click(function(){
                $("#newlesson").load("./includes/newlesson.inc"); 
            });
$("#page").click(function(){
                $("#newlesson").load("./includes/page.inc"); 
            });
$("#question").click(function(){
                $("#newlesson").load("./includes/question.inc"); 
            });
$("#login").click(function(){
				$("#userforms").load("./includes/login.php");
			});
});