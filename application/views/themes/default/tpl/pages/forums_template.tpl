<div class="panel panel-primary">
    <div class="panel-heading">
        <p class="lead">
            <strong>All Discussions</strong>
            <!-- IF {auth->logged_in} -->
            {new_discussion_button}
            <!-- END -->
        </p>
    </div>

    <div class="panel-body">
        <ul class="media-list">
        <!-- BEGIN discussions -->
            <li class="media">
                    <a class="pull-left" href="#">
                        {gravatar}
                    </a>
                    <div class="media-body">
                        <!-- IF {auth->logged_in} -->
                        <div class="col-md-5">
                        <!-- ELSE -->
                        <div class="col-md-6">
                        <!-- END -->
                            <div class="row">
                                <h4 class="media-heading">{discussion_name} <small><em>{closed}{sticky}</em></small></h4>
                            </div>
                            <div class="row">
                                <p><span class="fa fa-clock-o"></span> <em>{last_comment_date}</em></p>
                            </div>
                            <div class="row">
                                <p>
                                    {category}

                                    <!-- BEGIN {tags} -->
                                    {tag}
                                    <!-- END {tags} -->
                                </p>
                            </div>
                        </div> <!-- col-md-6 -->
                        <div class="col-md-4">
                                <a href="#">
                                    {last_comment_gravatar}
                                </a>
                            <p class="text-center">{last_comment_by}</p>
                        </div>
                        <div class="col-md-2">
                            <div class="btn-group-vertical pull-right">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-danger btn-sm"><i class="fa fa-heart"></i> {hearts}</a>
                                </div>
                                <div class="btn-group">
                                    <a href="#" class="btn btn-info btn-sm"><i class="fa fa-comment"></i> {comments}</a>
                                </div>
                            </div>
                        </div> <!-- col-md-2 -->
                        <!-- IF {auth->logged_in} -->
                        <div class="col-md-1">
                            <div class="btn-group-vertical">
                                {edit_button}
                                {delete_button}
                            </div>
                        </div>
                        <!-- END -->
                    </div>
            </li>
            <hr />
        <!-- END discussions -->
            <div class="text-center" id="pagination">
                {pagination}
            </div>
        </ul>
    </div>
</div>