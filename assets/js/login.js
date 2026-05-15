$(document).on('submit', '#form-login', function(event) {
        event.preventDefault();
        /* Act on the event */
        var un = $('#username').val();
        var pw = $('#password').val();
        $.ajax({
              url: 'data/user_login.php',
              type: 'post',
              dataType: 'json',
              data: {
                un:un,
                pw:pw
              },
              success: function (data) {
                if(data.valid == true){
                  window.location = data.url;
                } else {
                  alert('Invalid Username / Password!');
                  if(data.error){
                    console.error('Login error:', data.error);
                  }
                }
              },
              error: function(jqXHR, textStatus, errorThrown){
                alert('Login request failed: ' + textStatus + ' ' + errorThrown);
                console.error('Login response text:', jqXHR.responseText);
              }
            });//end a req
      });