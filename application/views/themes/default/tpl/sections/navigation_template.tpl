<div class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Navbar Logo -->
            {logo}
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="#"><em class="fa fa-home"></em> Home</a></li>
                <li><a href="members"><em class="fa fa-users"></em> Members</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i> {lang->frontend->text_account} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <!-- if {auth->logged_in} -->
                        <li><a href="#">Profile</a></li>
                        <li role="presentation" class="divider"></li>
                        <li>{sign_out_link}</li>
                        <!-- ELSE -->
                        <li>{sign_in_link}</li>
                        <li><a href="#"><i class="fa fa-pencil-square-o"></i> Sign Up</a></li>
                        <!-- END -->
                    </ul>
                </li>
            </ul>
        </div><!-- nav-collapse -->
    </div><!-- container -->
</div><!-- nav -->