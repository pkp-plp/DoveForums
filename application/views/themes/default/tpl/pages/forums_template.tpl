<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <h4 class="pull-left">{page_title}</h4>
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group btn-group-xs">
                        {btn_all_discussions}
                    </div>
                    <div class="btn-group btn-group-xs">
                        {btn_unanswered_discussions}
                    </div>
                    <!-- IF {auth->logged_in} -->
                    <div class="btn-group btn-group-xs">
                        {btn_my_discussions}
                    </div>
                    <!-- END -->
                </div>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <ul class="media-list">
            <hr />
        <!-- BEGIN discussions -->
            <li class="media">
                    <div class="media-body">
                        <!-- IF {auth->logged_in} -->
                        <div class="col-md-9">
                        <!-- ELSE -->
                        <div class="col-md-10">
                        <!-- END -->
                            <div class="pull-left">
                                {gravatar}
                            </div>
                            <p class="media-heading">&nbsp;&nbsp;<strong>{discussion_name}</strong></p>
                            <p class="text-muted">&nbsp;&nbsp;{category}&nbsp;&nbsp;&nbsp;{tag}&nbsp;&nbsp;&nbsp;{announcement}&nbsp;&nbsp;&nbsp;{closed}&nbsp;&nbsp;&nbsp;<small><i class="fa fa-calendar"></i> {last_comment_date}&nbsp;&nbsp;&nbsp;<i class="fa fa-user"></i> {last_comment_by}</small></p>
                        </div> <!-- col-md-6 -->
                        <div class="col-md-1">
                            <div class="btn btn-info btn-sm">
                                <i class="fa fa-thumbs-up fa-2x text-center"></i>
                                <br>
                                <span>{likes}</span>
                            </div>
                        </div> <!-- col-md-2 -->
                        <div class="col-md-1">
                            <div class="btn btn-info btn-sm">
                                <i class="fa fa-comment fa-2x"></i>
                                <br>
                                <span>{comments}</span>
                            </div>
                        </div>
                        <!-- IF {auth->logged_in} AND {owned} == 1 -->
                        <div class="col-md-1">
                            <div class="btn-group-vertical">
                                <!-- IF {permissions->edit_discussions->value} == 1 -->
                                {edit_button}
                                <!-- END -->

                                <!-- IF {permissions->delete_discussions->value} == 1 -->
                                {delete_button}
                                <!-- END -->
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