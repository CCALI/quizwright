<!-- Form for creating a new quiz True/False page -->
<form class="form-horizontal"  id="page-quiz-form">

    
    <fieldset>
        <legend>True/False Quiz Question</legend> <!-- Textarea -->

		  <input type="hidden" name="page-type" value="quiz-tf" />
		  

			
        <div class="form-group">
			
            <label class="col-sm-2 control-label" for="page">Text of your question</label>

            <div class="col-sm-8">
                <textarea name="page-question"  class="form-control"></textarea>
            </div>
        </div>
        
		<div class="xform-inline">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="page">Correct choice?</label>

                <div class="col-sm-2">
                    
		<div class="btn-group" data-toggle="buttons">
  
	  <label class="btn btn-primary ">    <input type="radio" name="true-is-correct" value="true" autocomplete="off"> True   </label>
	<label class="btn btn-primary">    <input type="radio" name="true-is-correct" value="false" autocomplete="off"> False   </label>
	</div>
                </div> 
            </div>
        </div>
		
		
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Feedback (Optional)</label>

            <div class="col-sm-8">
                <textarea name="page-feedback"  class="form-control"></textarea>
            </div>
        </div>
		  
		   
        <!-- Optional extra page information -->
 <div class="nav-stacked form-group">
                <div class="nav-header ">
						<a href="#" data-toggle="collapse" data-target="#page-extra">More <i class="glyphicon glyphicon-chevron-right"></i></a>
                    <div class="nav nav-stacked collapse " id="page-extra">
							
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="page-topic">Topic Area</label>
 
            <div class="col-sm-8">
					<select name="page-topic" id="page-topic" class="form-control"></select>
            </div>
        </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="page-topic">Attribution (optional)</label>
 
            <div class="col-sm-8">
		      <input id="page-attribution" name="page-attribution" placeholder="" class="form-control" type="text" value="">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Teacher Notes (Optional)</label>

            <div class="col-sm-8">
                <textarea name="page-notes"  class="form-control"></textarea>
            </div>
        </div>
		  
						  </div></div> </div></div> 
						  
        <!-- Button -->

        <div class="form-group">
            <label class="col-sm-2 control-label" for="page-submit">Done?</label>

            <div class="col-sm-3">
                <button id="page-submit" name="page-submit" class="btn btn-primary">Add another question</button>
            </div>
            <div class="col-sm-2">
            or</div>
            <div class="col-sm-3">
                <button id="page-submit-prepare" name="page-submit-prepare" class="btn btn-primary">Prepare the Quiz</button>
            </div>
        </div>
        
        
         
        
    </fieldset>
</form>


<script>
$('[data-toggle=collapse]').unbind().click(function(){
	// toggle icon
	$(this).find("i").toggleClass("glyphicon-chevron-right glyphicon-chevron-down");
});

loadCALITopics($('#page-topic'));
$('#page-submit').click(function(){ // Save page let author add new page
	$.post( "./includes/page-update.php", $( "#page-quiz-form" ).serialize() ,function( data ) {
		console.log(data);
		$("#main-panel").load("./includes/page-quiz.inc"); 
	});
	return false;
});
$('#page-submit-prepare').click(function(){ // Save page let author prepare quiz
	$.post( "./includes/page-update.php", $( "#page-quiz-form" ).serialize() ,function( data ) {
		console.log(data);
		$("#main-panel").load("./includes/quiz-prep-pages.php"); 
	});
	return false;
});

</script>
