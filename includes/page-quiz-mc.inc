<!-- Form for creating a new quiz Multiple Choice page -->
<form class="form-horizontal"  id="page-quiz-form">

    
    <fieldset>
        <legend>Multiple Choice Quiz Question</legend> <!-- Textarea -->

		  <input type="hidden" name="page-type" value="quiz-mc" />
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Text of your question</label>

            <div class="col-sm-8">
                <textarea name="page-question"  class="form-control">
</textarea>
            </div>
        </div>
        
        
        
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Correct choice text</label>

            <div class="col-sm-8">
                <textarea name="page-choice-correct-text"   class="form-control">
</textarea>
            </div>
        </div>
        
        
        
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Wrong choice #1</label>

            <div class="col-sm-8">
                <textarea  name="page-choice-wrong-1-text" class="form-control" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Wrong choice #2<br>(optional)</label>

            <div class="col-sm-8">
                <textarea  name="page-choice-wrong-2-text" class="form-control" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Wrong choice #3<br>(optional)</label>

            <div class="col-sm-8">
                <textarea name="page-choice-wrong-3-text" class="form-control" />
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
            <label class="col-sm-2 control-label" for="page">Notes (Optional)</label>

            <div class="col-sm-8">
                <textarea name="page-notes"  class="form-control"></textarea>
            </div>
        </div>
		  
						  </div></div> </div></div> 
						  
<div class="validate"></div>
		  
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
cawLoadCALITopics($('#page-topic'));
cawCKEditor('page-question,page-feedback,page-notes');
cawCKEditor('page-choice-correct-text,page-choice-wrong-1-text,page-choice-wrong-2-text,page-choice-wrong-3-text');
function validate()
{
	
	if ((cawCKEditorLength('page-question')==0)  || (cawCKEditorLength('page-choice-correct-text')==0) || (cawCKEditorLength('page-choice-wrong-1-text')==0))
	{	// validate fields
		$('.validate').html('<div class="alert alert-danger" role="alert">An MC quiz question requires a question, a correct choice and at least one wrong choice.</div>');
		return false;
	}
	return true;
}
	
$('#page-submit').click(function(){ // Save page let author add new page
	cawCKEditorUpdates();
	if (!validate()) return false;
	$.post( "./includes/page-update.php", $( "#page-quiz-form" ).serialize() ,function( data ) {
		$("#main-panel").load("./includes/page-quiz.php");
		window.scrollTo(0, 0);
	});
	return false;
});
$('#page-submit-prepare').click(function(){ // Save page let author prepare quiz
	cawCKEditorUpdates();
	if (!validate()) return false;
	$.post( "./includes/page-update.php", $( "#page-quiz-form" ).serialize() ,function( data ) {
		$("#main-panel").load("./includes/quiz-prep-pages.php"); 
	});
	return false;
});

</script>
