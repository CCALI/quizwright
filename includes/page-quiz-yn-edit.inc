<legend>Yes/No Quiz Question</legend>
 <input type="hidden" name="page-type" value="quiz-yn" />
 <div class="xform-inline">
	 <div class="form-group">
		  <label class="col-sm-2 control-label" for="page">Correct choice?</label>
		  <div class="col-sm-4">
			 <div class="btn-group" data-toggle="buttons">
				 <label class="btn btn-default ">   <input type="radio" name="yes-is-correct"  value="true" autocomplete="off"> Yes   </label>
				 <label class="btn btn-default">    <input type="radio" name="yes-is-correct" value="false" autocomplete="off"> No   </label>
			 </div>
		 </div> 
	 </div>
</div>


<script>
cawSetRB('yes-is-correct',data['yes-is-correct']);
</script>
