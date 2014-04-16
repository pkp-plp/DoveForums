<div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <p class="lead">
            <strong>{lang->frontend->panel_categories}</strong>
            <!-- IF {auth->is_admin} -->
            <a class="btn btn-success btn-icon btn-sm pull-right" href="#"><span class="glyphicon glyphicon-plus"></span> New Category</a>
            <!-- END -->
        </p>
    </div>

    <ul class="list-group">
    <!-- BEGIN categories -->
        <li class="list-group-item">{category_name}</li>
    <!-- END categories -->
    </ul>
</div>

<div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <p class="lead">
            <strong>{lang->frontend->panel_members}</strong>
            <span class="label label-warning pull-right">{member_count}</span>
        </p>
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