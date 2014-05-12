<div class="panel panel-primary">
    <div class="panel-heading">
        <p class="lead">
            {discussion_name}
        </p>
    </div>

    <div class="panel-body">
        <!-- BEGIN comments -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><small><strong>{user_info->username}</strong></small></h4>
                </div>

                <div class="panel-body">
                    <div class="col-md-2">
                        <div class="text-center">
                            <p class="text-success">{user_info->group}</p>
                            <a href="#">
                                {user_info->gravatar}
                            </a>
                            <p class="text-info">{user_info->rank} <br /><small>({user_info->user_xp}/{user_info->max_xp} xp)</small></p>
                                <ul class="list-unstyled">
                                    <li class="small"><span>0 Posts</span></li>
                                </ul>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <p class="text-muted"><em>Posted {comment_info->created_date} ago</em></p>
                        <p>{comment_info->comment}</p>
                        <hr>
                        <p class="text-muted"><small><em>{user_info->signature}</em></small></p>
                    </div>
                </div>

                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="#" class="btn btn-success btn-xs"><i class="fa fa-thumbs-up"></i></a>
                                <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-thumbs-down"></i></a>
                                <a href="#" class="btn btn-success btn-xs"> This answered my question</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- END comments -->
    </div>
</div>