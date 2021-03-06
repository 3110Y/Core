<div class=" uk-width-{GRID}@m">

    <label for="{ID}" class="uk-form-label" style="vertical-align: top;">{LABEL}</label>
    <div  class="fileUpload-dflex" id="{ID}-cont-all" style="display: flex;flex-direction: column;width: 200px">
       {include 'file.tpl'}
        <div id="{ID}" data-action="{URL}/{PARENT_URL}/api/field/file/save/{ROW_ID}/{TABLE}/{ID}" class="test-upload uk-placeholder uk-text-center"  style="margin-top: 0;{READONLY}" >
            <span uk-icon="icon: cloud-upload"></span>
            <div uk-form-custom>
                <input type="file" multiple  name="{ID}"  class="{CLASS}" placeholder="{PLACEHOLDER}" style="{STYLE}"  accept="{ACCEPT}">
                <span class="uk-link" style="color: #5897fb; border-bottom: 1px dotted #5897fb; ">Загрузка</span>
            </div>
        </div>
        <progress id="progressbar-{ID}" class="uk-progress" value="0" max="100" hidden></progress>
    </div>
    {INIT}

</div>
