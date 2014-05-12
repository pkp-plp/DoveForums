<div class="col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <p class="lead">
                {lang->frontend->panel_sign_in}
            </p>
        </div>

        <div class="panel-body">
            {form_open}
                <div class="form-group">
                    <div class="input-group input-icon">
                        <span class="input-group-addon">
                            <i class="fa fa-envelope fa-fw"></i>
                        </span>
                        {username_field}
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-icon">
                        <span class="input-group-addon">
                            <i class="fa fa-key fa-fw"></i>
                        </span>
                        {password_field}
                    </div>
                </div>
                <div class="form-group">
                    <div class="pull-right">
                        {submit_button}
                    </div>
                </div>
            {form_close}
        </div>

        <div class="panel-footer">
            <div class="text-center">
                {forgot_password_link}
            </div>
        </div>
    </div>
</div>