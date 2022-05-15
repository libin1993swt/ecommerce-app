<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Laravel 8|7|6 CRUD App Example</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

      <style>
         body {
         margin: 0;
         font-family: "Lato", sans-serif;
         }

         .sidebar {
         margin: 0;
         padding: 0;
         width: 200px;
         background-color: #f1f1f1;
         position: fixed;
         height: 100%;
         overflow: auto;
         }

         .sidebar a {
         display: block;
         color: black;
         padding: 16px;
         text-decoration: none;
         }
         
         .sidebar a.active {
         background-color: #04AA6D;
         color: white;
         }

         .sidebar a:hover:not(.active) {
         background-color: #555;
         color: white;
         }

         div.content {
         margin-left: 200px;
         padding: 1px 16px;
         height: 1000px;
         }

         @media screen and (max-width: 700px) {
         .sidebar {
            width: 100%;
            height: auto;
            position: relative;
         }
         .sidebar a {float: left;}
         div.content {margin-left: 0;}
         }
      </style>
   </head>
   <body>
      @include('sidebar')
      <div class="container">
         @yield('content')
      </div>
      <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" type="text/js"></script>
         @yield('js-script')
   </body>
</html>