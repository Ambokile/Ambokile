<!DOCTYPE html>
<html lang="en">

<head>
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        #loginErrorMsg{
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
<div id="login-overlay" class="modal-dialog" style="margin-top: 10%">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Login to site.com</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-6">
                    <div class="well">
                        <form id="loginForm" method="POST" action="{{route('login')}}" novalidate="novalidate">
                            {{ csrf_field() }}
                            <div id="loginErrorMsg" class="alert alert-error @if(count($errors) > 0) show @else hide @endif">Wrong username or password</div>
                            <div class="form-group">
                                <label for="username" class="control-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="" required="" title="Please enter you username" placeholder="example@gmail.com">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="" required="" title="Please enter your password">
                                <span class="help-block"></span>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Login</button>
                            <a href="/forgot/" class="btn btn-default btn-block">Help to login</a>
                        </form>
                    </div>
                </div>
                <div class="col-xs-6">
                    <p class="lead">Register now for <span class="text-success">FREE</span></p>
                    <ul class="list-unstyled" style="line-height: 2">
                        <li><span class="fa fa-check text-success"></span> See all your orders</li>
                        <li><span class="fa fa-check text-success"></span> Fast re-order</li>
                        <li><span class="fa fa-check text-success"></span> Save your favorites</li>
                        <li><span class="fa fa-check text-success"></span> Fast checkout</li>
                        <li><span class="fa fa-check text-success"></span> Get a gift <small>(only new customers)</small></li>
                        <li><a href="/read-more/"><u>Read more</u></a></li>
                    </ul>
                    <p><a href="/new-customer/" class="btn btn-info btn-block">Yes please, register now!</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>