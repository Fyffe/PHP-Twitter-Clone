$(document).ready(function() {
  $('.input-username').on('change', function() {
    $('.ajax-helper').remove();
    $(this).removeClass('form-control-error form-control-success form-control-warning').parent().removeClass('has-error has-success has-warning');
    var username = $(this).val();

    var req = $.ajax({
      url: '/kolu/ajax/form-validation.php',
      method: 'POST',
      data: {
        username: username
      }
      
    });

    req.done(function(data) {
      var data = $.parseJSON(data);
      if (data.type == "danger") {
        $('.input-username').addClass('form-control-error').parent().addClass('has-error').parent().prepend("<small class='text-danger center-block text-center ajax-helper'>" + data.msg + "</small>");
        $('.btn-register').attr('disabled', 'disabled');
      }

      if (data.type == "success") {
        $('.input-username').addClass('form-control-success').parent().addClass('has-success').parent().prepend("<small class='text-success center-block text-center ajax-helper'>" + data.msg + "</small>");
        $('.btn-register').removeAttr('disabled');
      }

      if (data.type == "warning") {
        $('.input-username').addClass('form-control-warning').parent().addClass('has-warning').parent().prepend("<small class='text-warning center-block text-center ajax-helper'>" + data.msg + "</small>");
        $('.btn-register').attr('disabled', 'disabled');
      }

    });

    req.fail(function(jqXHR, data) {
      var data = $.parseJSON(data);
      $('.form-wrapper').append("<div class='alert alert-warning alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Something went wrong...</div>");
    });

  });


});
