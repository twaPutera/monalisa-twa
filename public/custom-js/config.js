$(document.body).on('click', '.js-submit-confirm', function (event) {
  event.preventDefault()
  var $form = $(this).closest('form')
  swal.fire({
    title: "Apakah anda yakin?",
    text: "Anda tidak dapat membatalkan proses ini!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: "Batal",
    cancelButtonColor: "#d5d5d5",
    closeOnConfirm: true
  }).then(function(result) {
    if (result.value) {
      $form.submit()
    }
  });
});

$(document.body).on('click', '.js-confirm', function (event) {
  event.preventDefault()
  var $form = $(this).closest('form')
  swal.fire({
    title: "Apakah anda yakin?",
    text: "Anda tidak dapat membatalkan proses ini!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: '#3598dc',
    confirmButtonText: 'Ya, konfirmasikan!',
    cancelButtonText: "Batal",
    cancelButtonColor: "#d5d5d5",
    closeOnConfirm: true
  }).then(function(result) {
    if (result.value) {
      $form.submit()
    }
  });
});

$('.number-format').keyup(function () {
  this.value = this.value.replace(/[^0-9\.]/g,'');
});

$(document).on('keydown', 'input[pattern]', function(e){
  var input = $(this);
  var oldVal = input.val();
  var regex = new RegExp(input.attr('pattern'), 'g');

  setTimeout(function(){
    var newVal = input.val();
    if(!regex.test(newVal)){
      input.val("");
    }
  }, 0);
});

$(document.body).on('click', '.detail-with-modal', function (event) {
  event.preventDefault()
  var myModal = $(this).data('target');
  $(myModal+' .modal-content').load(
    $(this).attr('href'),
    function(response, status, xhr) {
      if (status === 'error') {
        //console.log('got here');
        $(myModal+'.modal-content').html('<p>Maaf, terjadi kesalahan program :' + xhr.status + ' ' + xhr.statusText+ '</p>');
      }
      return this;
    }
    );
});