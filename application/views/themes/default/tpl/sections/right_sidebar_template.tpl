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
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <img src="{T_Folder}/img/gallery/pic1.png" class="img-rounded img-responsive">
                        </div>
                        <div class="col-ld-4 col-md-4 col-sm-4 col-xs-4">
                            <img src="{T_Folder}/img/gallery/pic2.png" class="img-rounded img-responsive">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <img src="{T_Folder}/img/gallery/pic3.png" class="img-rounded img-responsive">
                        </div>
                    </div>
                    <br/>
                    <!-- Member Row 2 -->
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <img src="{T_Folder}/img/gallery/pic5.png" class="img-rounded img-responsive">
                        </div>
                        <div class="col-ld-3 col-md-3 col-sm-3 col-xs-3">
                            <img src="{T_Folder}/img/gallery/pic6.png" class="img-rounded img-responsive">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <img src="{T_Folder}/img/gallery/pic7.png" class="img-rounded img-responsive">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <img src="{T_Folder}/img/gallery/pic8.png" class="img-rounded img-responsive">
                        </div>
                    </div>
                    <br/>
                    <div class="text-center">
                        <button class="btn btn-primary">View More</button>
                    </div>
                </div>
            </div>

        </div>
    </div>