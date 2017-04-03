<!-- Form for select a new quiz page type -->
<form class="form-horizontal"  id="page-quiz-form">

    
    <fieldset>
        <legend>Quiz Question</legend>
		  
        
        <!-- Button -->

        <div class="form-group">
            <label class="col-sm-2 control-label" for="page-submit">Type of question?</label>

            <div class="col-sm-3">
                <button id="page-quiz-tf"  class="btn btn-primary">True/False</button>
            </div>
            <div class="col-sm-3">
                <button id="page-quiz-mc" class="btn btn-primary">Multiple Choice</button>
            </div>
        </div>
        
        
         
        
    </fieldset>
</form>


<script>
$('#page-quiz-tf').click(function(){  
	$("#main-panel").load("./includes/page-quiz-tf.inc"); 
	return false;
});
$('#page-quiz-mc').click(function(){  
	$("#main-panel").load("./includes/page-quiz-mc.inc"); 
	return false;
});

</script>
