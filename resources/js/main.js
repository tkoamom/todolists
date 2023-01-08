$(document).ready(function(){

	var items = getFromLocal('memos');
	var index;
	loadList(items);
	// if input is empty disable button
	$('#task-list-button').prop('disabled', true);
	$('#task-add-input').keyup(function(){
		if($(this).val().length !== 0) {
			$('#task-list-button').prop('disabled', false);
		} else {
			$('#task-list-button').prop('disabled', true);
		}
	});
	// bind input enter with button submit
	$('#task-add-input').keypress(function(e){
		if(e.which === 13) {
			if ($('#task-add-input').val().length !== 0)
				$('#task-list-button').click();
		}
	});
	$('#task-list-button').click(function(){
		var value = $('#task-add-input').val();
		items.push(value);
		//console.log(items[0]);
		$('#task-add-input').val('');
		loadList(items);
		storeToLocal('memos', items);
		// set button to
		$('#task-list-button').prop('disabled', true);
	});
	// delete one item
	$('.task-list').delegate(".task-delete", "click", function(event){
		event.stopPropagation();
		index = $('.task-delete').index(this);
		$('.task-item').eq(index).remove();
		items.splice(index, 1);
		storeToLocal('memos', items);
        $('input[name="tasks"]').val(items.join('&and'))

	});

	// edit panel
	$('.task-list').delegate('.task-edit', 'click', function(){
		index = $('.task-edit').index(this);
		var content = items[index];
		console.log(content);
		$('#edit-input').val(content );
	});

	$('#edit-button').click(function(){
		items[index] = $('#edit-input').val();
		loadList(items);
		storeToLocal("memos", items);
	});

	// loadList
	function loadList(items){
		$('.task-item').remove();
		if(items.length > 0) {
			for(var i = 0; i < items.length; i++) {
				$('.task-list').append('<li class= "list-group-item task-item">' + items[i] + '<div class="buttons"><div class="task-edit" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-pen-to-square"></i></div><div class="task-delete"><i class="fa-solid fa-xmark"></i></div></div></li>');
			}
		}
        $('input[name="tasks"]').val(items.join('&and'))
	};

	function storeToLocal(key, items){
		localStorage[key] = JSON.stringify(items);
	}

	function getFromLocal(key){
		if(localStorage[key])
			return JSON.parse(localStorage[key]);
		else
			return [];
	}

});