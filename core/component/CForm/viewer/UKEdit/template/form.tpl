<div class="cform w-hundred">
    <div class="cf-caption {CAPTION_CLASS}">
        <span>{CAPTION}</span>
    </div>
    <form action="{URL}/save/{ID}" id="s{ID}" method="post" class="edit" enctype="multipart/form-data">
        <div class="cf-table">
            {FIELDS}
                {COMPONENT}
            {/FIELDS}
        </div>
        <div class="item-action ">
        </div>
        <div class="group-action {CLASS_ACTION_BOTTOM_ITEM}">
            <div class="uk-button-group">
                {ROWS}
                    {COMPONENT}
                {/ROWS}
            </div>
        </div>
    </form>
</div>
<div class="cform-after {CLASS_ACTION_BOTTOM_ITEM}"></div>