<div class="panel panel-primary">
    <div class="panel-heading">
        <p class="lead">
            <strong>New Discussion</strong>
        </p>
    </div>

    <div class="panel-body">
        {form_open}
            <div class="form-group">
                {category_label}
                {category_field}
            </div>
            <div class="form-group">
                {discussion_name_label}
                {discussion_name_field}
            </div>
            <div class="form-group">
                {comment_label}
                {comment_field}
            </div>

            <div class="form-group">
                {tags_label}
                {tags_field}
            </div>

            <div class="form-group">
                <div class="pull-right">
                    {clear_button}
                    {submit_button}
                </div>
            </div>
        {form_close}
    </div>
</div>