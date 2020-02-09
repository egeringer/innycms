<!--begin::Modal-->
<div class="modal fade" id="cleanBucket" tabindex="-1" role="dialog" aria-labelledby="cleanBucket" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cleanBucketLabel">{'Confirm Clean Bucket Action'|_t}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>
                        {'You are about to clean bucket files.'|_t}
                        <br/>
                        {'<strong>All files</strong> that are <strong>not being used</strong> on your site will be <strong>permanently deleted</strong>.'|_t}
                        <br/>
                        {'This action <strong>cannot be undone</strong>.'|_t}
                    </p>
                    <div class="form-group m-form__group">
                        <label for="cleanBucketPass">{'Enter your Password'|_t}</label>
                        <input type="password" class="form-control m-input" id="cleanBucketPass" placeholder="{'Password'|_t}" autofocus>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                    <button type="submit" class="btn btn-danger" id="cleanBucketSubmit">{'Clean Bucket'|_t}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--end::Modal-->

<!--begin::Modal-->
<div class="modal fade" id="emptyBucket" tabindex="-1" role="dialog" aria-labelledby="emptyBucket" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emptyBucketLabel">{'Confirm Empty Bucket Action'|_t}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>
                        {'You are about to clean bucket files.'|_t}
                        <br/>
                        {'<strong>All files</strong> on your site will be <strong>permanently deleted</strong>.'|_t}
                        <br/>
                        {'This action <strong>cannot be undone</strong>.'|_t}
                    </p>
                    <div class="form-group m-form__group">
                        <label for="emptyBucketPass">{'Enter your Password'|_t}</label>
                        <input type="password" class="form-control m-input" id="emptyBucketPass" placeholder="{'Password'|_t}" autofocus>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                    <button type="submit" class="btn btn-danger" id="emptyBucketSubmit">{'Empty Bucket'|_t}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--end::Modal-->

<!--begin::Modal-->
<div id="deleteItem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{'Confirm File Deletion'|_t}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="{'Close'|_t}"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p> {'You are about to delete a file from the bucket.'|_t} </p>
                <p> {'This means that <strong id="modal-item-data"></strong> will be <strong>permanently deleted</strong>.'|_t} </p>
                {'This action <strong>cannot be undone</strong>.'|_t}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{'Cancel'|_t}</button>
                <a class="btn btn-danger" href="#">{'Delete Item'|_t}</a>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->