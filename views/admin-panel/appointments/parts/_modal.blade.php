<!-- Modal -->
<div class="modal fade" id="pageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="pageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pageModalLabel">Search Guest</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" class="mb-2" id="searchGuestForm" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter PRN or NID" aria-label="Enter PRN or NID" name="search-query" id="search-query" required>
                        <button type="submit" name="submit" id="submit-form" class="btn input-group-text btn-primary waves-effect waves-light" >Search</button>
                    </div>
                </form>
                <div id="search-results"></div>
            </div>
        </div>
    </div>
</div>