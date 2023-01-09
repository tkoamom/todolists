$(document).ready(function(){

    if (window.location.href.includes('catalog/')){
        var items = $('input[name="tasks"]').val().split('&and');
        loadList(items);
    }
	var index;
	// if input is empty disable button
	$('#task-list-button').prop('disabled', true);
	$('#task-add-input').keyup(function(event){
        event.preventDefault();
		if($(this).val().length !== 0) {
			$('#task-list-button').prop('disabled', false);
		} else {
			$('#task-list-button').prop('disabled', true);
		}
	});
	// bind input enter with button submit
	$('#task-add-input').keypress(function(e){
		if(e.which === 13) {
            e.preventDefault();
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
                if (items[i] === ''){
                    items.splice(i,1)
                }
                else {
                    $('.task-list').append('<li class= "list-group-item task-item">' + items[i] + '<div class="buttons"><div class="task-edit" data-bs-toggle="modal" data-bs-target="#editModal"><a class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen-to-square"></i></a></div><div class="task-delete"><a class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-xmark"></i></a></div></div></li>');
                }
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

    const deleteModal = document.getElementById('deleteModal')

    deleteModal.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget
        // Extract info from data-bs-* attributes
        const id = button.getAttribute('data-bs-id')
        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        const modalTitle = deleteModal.querySelector('.modal-title')
        const modalBodyForm = deleteModal.querySelector('#delete_list')
        const modalBodyFormAction = modalBodyForm.getAttribute('action')
        console.log(modalBodyFormAction)

        modalTitle.textContent = `Are you sure you want to delete this To do list ` + id + ' ?'
        $('#delete_list').attr('action', modalBodyFormAction.slice(0, -id.length) + id);
    })

    const shareModal = document.getElementById('shareModal')

    shareModal.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget
        // Extract info from data-bs-* attributes
        const id = button.getAttribute('data-bs-id')
        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        const modalBodyForm = shareModal.querySelector('#share_list')
        const modalBodyFormAction = modalBodyForm.getAttribute('action')
        console.log(modalBodyFormAction)

        $('#share_list').attr('action', modalBodyFormAction.slice(0, -id.length) + id);
    })


});
