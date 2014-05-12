<!-- IF {auth->logged_in} -->
    <div class="row">
        <div class="col-md-12">

            <!-- IF {permissions->create_discussions->value} == 1 -->
            {new_discussion_button}
            <!-- END -->

        </div>
    </div>
    <br />
<!-- END -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4><strong>{lang->frontend->panel_categories}</strong></h4>
                </div>

                <ul class="list-group">
                <!-- BEGIN categories -->
                    <li class="list-group-item">{category_name}</li>
                <!-- END categories -->
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4><strong>{lang->frontend->panel_members}</strong><span class="label label-warning pull-right">{member_count}</span></h4>
                </div>

                <div class="panel-body">
                    <!-- Member Row 1 -->
                    <div class="row">
                        <!-- BEGIN members -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            {member}
                        </div>
                        <!-- END members -->
                    </div>
                    <br/>
                    <div class="text-center">
                        <button class="btn btn-primary">View More</button>
                    </div>
                </div>
            </div>

        </div>
    </div>