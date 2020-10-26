<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Image Uploader Demo</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown dropdown-notifications">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i data-count="0" class="glyphicon glyphicon-bell notification-icon"></i>
                            Notifications (<span class="notif-count">0</span>) <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>


<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        @yield('content')
      </div>
    </div>
</div>

<style>
    .notification.active {min-width: 450px; margin: 10px}
</style>

  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="//js.pusher.com/3.1/pusher.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script type="text/javascript">
  $(function () {
      var notificationsWrapper   = $('.dropdown-notifications');
      var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
      var notificationsCountElem = notificationsToggle.find('i[data-count]');
      var notificationsCount     = parseInt(notificationsCountElem.data('count'));
      var notifications          = notificationsWrapper.find('ul.dropdown-menu');

      if (notificationsCount <= 0) {
          notificationsWrapper.hide();
      }

      // Enable pusher logging - don't include this in production
      Pusher.logToConsole = true;

      var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
          encrypted: true,
          cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
      });

      // Subscribe to the channel we specified in our Laravel Event
      var channel = pusher.subscribe('image-uploader');

      // Bind a function to a Event (the full Laravel class)
      channel.bind('image-resized', addNotification);

      $.ajax('{{ route('image-uploader.api-images') }}')
        .done(function (response) {
            for (var img in response.reverse()) {
                var images = {};
                response[img].copies.forEach(function (imgCopy) {
                    images[imgCopy.size_type] = imgCopy.url;
                });
                addNotification({message: 'Image ' + response[img].original_name + ' was resized', images: images});
            }
        });


      function addNotification(data) {
          var existingNotifications = notifications.html();
          var thumbnail = data.images.thumbnail;
          var imagesHtml = [];
          for (var key in data.images) {
              imagesHtml.push(`<a href="${data.images[key]}">${key}</a>`);
          }
          imagesHtml = imagesHtml.join(' ');

          var newNotificationHtml = `
          <li class="notification active">
              <div class="media">
                <div class="media-left">
                  <div class="media-object">
                    <img src="${thumbnail}" class="img-circle">
                  </div>
                </div>
                <div class="media-body">
                  <strong class="notification-title">${data.message}</strong>
                  <p class="notification-desc">Image copies were created: ${imagesHtml}</p>
                </div>
              </div>
          </li>`;

          notifications.html(newNotificationHtml + existingNotifications);

          notificationsCount += 1;
          notificationsCountElem.attr('data-count', notificationsCount);
          notificationsWrapper.find('.notif-count').text(notificationsCount);
          notificationsWrapper.show();
          if (notificationsToggle.attr('aria-expanded') != 'true') {
              notificationsToggle.dropdown('toggle');
          }
      }
  });
  </script>
</body>
</html>
