<legend>Multiple Choice Quiz Question</legend>
<input type="hidden" name="page-type" value="quiz-mc" />

<div class="form-group">
	 <label class="col-sm-2 control-label" for="page">Correct choice text</label>

	 <div class="col-sm-8">
		  <textarea name="page-choice-correct-text"   class="form-control" />
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

<script>
$('[name=page-choice-correct-text]').text(data['page-choice-correct-text']);
$('[name=page-choice-wrong-1-text]').text(data['page-choice-wrong-1-text']);
$('[name=page-choice-wrong-2-text]').text(data['page-choice-wrong-2-text']);
$('[name=page-choice-wrong-3-text]').text(data['page-choice-wrong-3-text']);
cawCKEditor('page-choice-correct-text,page-choice-wrong-1-text,page-choice-wrong-2-text,page-choice-wrong-3-text');
</script>
